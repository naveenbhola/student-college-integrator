<script>

var clickLoadMore=false;
var field='<?php echo $Balaji_gapInEdu; ?>';

window.onload = function(){
	  if (typeof(field)!='undefined' && field != '') {
	    $j('#loadMoreButton').trigger('click');
	  }
	  $j('#saveOnlineProceed').click(function(){
	    if (!clickLoadMore) {
	      $j('#loadMoreButton').trigger('click');
	      $j('html, body').animate({scrollTop : $j('#educationField').offset().top}, 500);
	      return false;
	    }
	    return true;
	    });
	  
  }
	

  function checkTestScore(obj){
	var key = obj.value.toLowerCase();
	var objects1 = new Array("Balaji_"+key.toUpperCase()+"CenterCode",key+"RollNumberAdditional",key+"ScoreAdditional",key+"PercentileAdditional",key+"RankAdditional",key+"DateOfExaminationAdditional");
	if(obj && $(key)){
	      if( obj.checked == false ){
		    $(key).style.display = 'none';
		    $(key+"1").style.display = 'none';
		    //Set the required paramters when any Exam is hidden
		    resetExamFields(objects1);
	      }
	      else{
		    $(key).style.display = '';
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
  
  
  function checkValidCampusPreference(id){
	if(id==1){id='preferredGDPILocation'; sId = 'Balaji_GDPI2'; tId='Balaji_GDPI3';}
	else if(id==2){ id='Balaji_GDPI2'; sId = 'preferredGDPILocation'; tId = 'Balaji_GDPI3'; }
	else {id='Balaji_GDPI3'; sId = 'preferredGDPILocation'; tId = 'Balaji_GDPI2'; }
	var selectedPrefObj = document.getElementById(id); 
	var selectedPref = selectedPrefObj.options[selectedPrefObj.selectedIndex].innerHTML;
	var selObj1 = document.getElementById(sId); 
	var selPref1 = selObj1.options[selObj1.selectedIndex].innerHTML;
	var selObj2 = document.getElementById(tId); 
	var selPref2 = selObj2.options[selObj2.selectedIndex].innerHTML;
	if( (selectedPref == selPref1 && selectedPref!='Select' ) || (selectedPref == selPref2 && selectedPref!='Select' ) ){
	  
		$(id +'_error').innerHTML = 'Same preference cant be set.';
		$(id +'_error').parentNode.style.display = '';
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
		$(id +'_error').innerHTML = '';
		$(id +'_error').parentNode.style.display = 'none';
	}
	return true;
  }
  
  function in_array(needle, haystack, argStrict)
	{
		var key = '', strict = !!argStrict;
		if (strict)
		{
			for (key in haystack)
			{
				if (haystack[key] === needle)
					return true;
			}
		}
		else
		{
			for (key in haystack)
			{
				if (haystack[key] == needle)
					return true;
			}
		}
		return false;
	}
	
	
	function checkForMultipleCourse(obj){
		var balajiArray = new Array();
		for(var i=1;i<=13;i++){
			if(i != parseInt(obj.id.replace("Balaji_coursePref","")) && $('Balaji_coursePref'+i).value !=''){
				balajiArray.push($('Balaji_coursePref'+i).value);
			}
		}
		if(in_array(obj.value,balajiArray)){
			$(obj.id+'_error').parentNode.style.display = '';
			$(obj.id+'_error').innerHTML = "<b>"+obj.value+"</b> is already selected at another preference. Please select another course."
			setTimeout(function(){
				obj.value = '';
			},1000);
		}
		
	}
	
	function showOtherSourceFields(id,obj){
         if(obj) {
	  
		$j('#'+id).show();
	 }else{
		$j('#'+id).hide();
		$j('#Balaji_nameOf'+id).val('');
	 }
    
  }
  
  function showSpecializationField(obj){ 
	    if(obj){
		      if(obj.value == "3 years degree" ){
			document.getElementById('grad3YearSpec').style.display = 'block';
			document.getElementById('Balaji_grad3Specialization').setAttribute('required','true');
			document.getElementById('grad4YearSpec').style.display = 'none';
			document.getElementById('Balaji_grad4Specialization').value = '';
			document.getElementById('4thYearLabel').style.display = 'none';
			document.getElementById('grad4thYearField').style.display = 'none';
			document.getElementById('Balaji_grad4Specialization').removeAttribute('required');
			document.getElementById('Balaji_grad4YearPassing').removeAttribute('required');
			document.getElementById('Balaji_grad4YearMarks').removeAttribute('required');
			document.getElementById('Balaji_grad4YearPassing').value='';
			document.getElementById('Balaji_grad4YearMarks').value='';
			document.getElementById('Balaji_grad4Specialization_error').innerHTML = '';
		        document.getElementById('Balaji_grad4Specialization_error').parentNode.style.display = 'none';
			document.getElementById('Balaji_grad4YearPassing_error').innerHTML = '';
		        document.getElementById('Balaji_grad4YearPassing_error').parentNode.style.display = 'none';
			document.getElementById('Balaji_grad4YearMarks_error').innerHTML = '';
		        document.getElementById('Balaji_grad4YearMarks_error').parentNode.style.display = 'none';
			
		      }else{
			document.getElementById('grad4YearSpec').style.display = 'block';
			document.getElementById('Balaji_grad4Specialization').setAttribute('required','true');
			document.getElementById('grad3YearSpec').style.display = 'none';
			document.getElementById('4thYearLabel').style.display = 'block';
			document.getElementById('grad4thYearField').style.display = 'block';
			document.getElementById('Balaji_grad3Specialization').removeAttribute('required');
			document.getElementById('Balaji_grad3Specialization_error').innerHTML = '';
		        document.getElementById('Balaji_grad3Specialization_error').parentNode.style.display = 'none';
			document.getElementById('Balaji_grad3Specialization').value = '';
			document.getElementById('Balaji_grad4YearPassing').setAttribute('required','true');
			document.getElementById('Balaji_grad4YearMarks').setAttribute('required','true');
			
					  
		      }
		  }
	}
  
</script>

<style>
  .secondPart { display: none; }
</style>
<div class='formChildWrapper'>
	<div class='formSection'>
		<ul>
			<?php if($action != 'updateScore'):?>
			<li>
				<h3 class="upperCase">Personal Information</h3>
				</li>
				<li>
				
				<div class='additionalInfoLeftCol' style="width:800px;">
				<label>Age : </label>
				<div class='fieldBoxSmall' style="width:210px;">
				<input class="textboxSmall" type='text' name='Balaji_ageInYear' id='Balaji_ageInYear'  validate="validateOnlineFormAge"   required="true"   caption="age in years"   minlength="2"   maxlength="2"     tip="Please enter your age in years till now"   title="Age "  value=''   /><span style="margin-left:5px;">Years</span>
				<?php if(isset($Balaji_ageInYear) && $Balaji_ageInYear!=""){ ?>
				  <script>
				      document.getElementById("Balaji_ageInYear").value = "<?php echo str_replace("\n", '\n', $Balaji_ageInYear );  ?>";
				      document.getElementById("Balaji_ageInYear").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_ageInYear_error'></div></div>
				</div>
				
				<div class='fieldBoxSmall' style="width:210px;">
				<input class="textboxSmall" type='text' name='Balaji_ageInMonth' id='Balaji_ageInMonth' validate="validateInteger"  required="true"   caption="age in months"   minlength="1"   maxlength="2"     tip="Please enter your age in months till now"   title="Age "  value='' /><span style="margin-left:5px;">Months</span>
				<?php if(isset($Balaji_ageInMonth) && $Balaji_ageInMonth!=""){ ?>
				  <script>
				      document.getElementById("Balaji_ageInMonth").value = "<?php echo str_replace("\n", '\n', $Balaji_ageInMonth );  ?>";
				      document.getElementById("Balaji_ageInMonth").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_ageInMonth_error'></div></div>
				</div>
				
				</div>
		      </li>
		      <li>
				
				<div class='additionalInfoLeftCol' style="width:99%">
				<label>Category: </label>
				<div class='fieldBoxLarge' style="width:550px;">
				<input type='radio'  validate="validateCheckedGroup"   required="true"   caption="the category"  name='Balaji_category' id='Balaji_category0'   value='SC' title="Category"   onmouseover="showTipOnline('Please select your appropriate category.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select your appropriate category.',this);" onmouseout="hidetip();" >SC</span>&nbsp;&nbsp;
				<input type='radio'  validate="validateCheckedGroup"   required="true"   caption="the category"  name='Balaji_category' id='Balaji_category1'   value='OBC'    title="Category"   onmouseover="showTipOnline('Please select your appropriate category.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select your appropriate category.',this);" onmouseout="hidetip();" >OBC</span>&nbsp;&nbsp;
				<input type='radio'  validate="validateCheckedGroup"   required="true"   caption="the category"  name='Balaji_category' id='Balaji_category2'   value='ST'    title="Category"   onmouseover="showTipOnline('Please select your appropriate category.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select your appropriate category.',this);" onmouseout="hidetip();" >ST</span>&nbsp;&nbsp;
				
				<input type='radio'  validate="validateCheckedGroup"   required="true"   caption="the category"  name='Balaji_category' id='Balaji_category3'   value='General'    title="Category"   onmouseover="showTipOnline('Please select your appropriate category.',this,-20);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select your appropriate category.',this,-30);" onmouseout="hidetip();" >General</span>&nbsp;&nbsp;
				<?php if(isset($Balaji_category) && $Balaji_category!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["Balaji_category"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $Balaji_category;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_category_error'></div></div>
				</div>
				</div>
                </li>
		<li>
			
				<div class='additionalInfoLeftCol'>
				<label>Married: </label>
				<div class='fieldBoxLarge'>
				<input type='radio'  validate="validateCheckedGroup"   required="true"   caption="yes or no"  name='Balaji_marriedStatus' id='Balaji_marriedStatus0'   value='Yes' title="Married"   onmouseover="showTipOnline('Are you married? If yes then please select Yes.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Are you married? If yes then please select Yes.',this);" onmouseout="hidetip();" >Yes</span>&nbsp;&nbsp;
				<input type='radio'  validate="validateCheckedGroup"   required="true"   caption="yes or no"  name='Balaji_marriedStatus' id='Balaji_marriedStatus1'   value='No'    title="Married"   onmouseover="showTipOnline('Are you married? If yes then please select Yes.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Are you married? If yes then please select Yes.',this);" onmouseout="hidetip();" >No</span>&nbsp;&nbsp;
				<?php if(isset($Balaji_marriedStatus) && $Balaji_marriedStatus!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["Balaji_marriedStatus"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $Balaji_marriedStatus;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_marriedStatus_error'></div></div>
				</div>
				</div>
			
			
				<div class='additionalInfoRightCol'>
				<label>Physically Handicapped: </label>
				<div class='fieldBoxLarge'>
				<input type='radio' onclick="showCertificateField(this);" validate="validateCheckedGroup"   required="true"   caption="yes or no"  name='Balaji_phyHandi' id='Balaji_phyHandi0'   value='Yes' title="Are you a physically handicapped candidate?"   onmouseover="showTipOnline('Do you have any physical disabilities? If yes then please select Yes.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Do you have any physical disabilities? If yes then please select Yes.',this);" onmouseout="hidetip();" >Yes</span>&nbsp;&nbsp;
				<input type='radio' onclick="showCertificateField(this);" validate="validateCheckedGroup"   required="true"   caption="yes or no"  name='Balaji_phyHandi' id='Balaji_phyHandi1'   value='No'    title="Are you a physically handicapped candidate?"   onmouseover="showTipOnline('Do you have any physical disabilities? If yes then please select Yes.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Do you have any physical disabilities? If yes then please select Yes.',this);" onmouseout="hidetip();" >No</span>&nbsp;&nbsp;
				<?php if(isset($Balaji_phyHandi) && $Balaji_phyHandi!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["Balaji_phyHandi"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $Balaji_phyHandi;?>") {
						      radioObj[i].checked = true;
				
					      }
				      }
				      
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_phyHandi_error'></div></div>
				</div>
				</div>
			</li>
		        <li>
				
				<div class='additionalInfoLeftCol' id="phyHandiAttach" style="display:none;">
				<label>Have Disability Certificate: </label>
				<div class='fieldBoxLarge'>
				<input type='radio'   caption="yes or no"  name='Balaji_phyHandiAttach' id='Balaji_phyHandiAttach0'   value='Yes' title="Are you attaching disability Certificate from competent authorities"   onmouseover="showTipOnline('Do you have a certificate for your physical disabilities that you can attach with this form? If yes then please select Yes.  Certificate should be from competent authorities.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Do you have a certificate for your physical disabilities that you can attach with this form? If yes then please select Yes.  Certificate should be from competent authorities.',this);" onmouseout="hidetip();" >Yes</span>&nbsp;&nbsp;
				<input type='radio'  caption="yes or no"  name='Balaji_phyHandiAttach' id='Balaji_phyHandiAttach1'   value='No'    title="Are you attaching disability Certificate from competent authorities"   onmouseover="showTipOnline('Do you have a certificate for your physical disabilities that you can attach with this form? If yes then please select Yes.  Certificate should be from competent authorities.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Do you have a certificate for your physical disabilities that you can attach with this form? If yes then please select Yes.  Certificate should be from competent authorities.',this,-10);" onmouseout="hidetip();" >No</span>&nbsp;&nbsp;
				<?php if(isset($Balaji_phyHandiAttach) && $Balaji_phyHandiAttach!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["Balaji_phyHandiAttach"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $Balaji_phyHandiAttach;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_phyHandiAttach_error'></div></div>
				</div>
				</div>
			</li>
			<li>
				<h3 class="upperCase">Family Information</h3>
			</li>
			<li style="width:100%;">
			      <div class='additionalInfoLeftCol'>
				<label>Father's Occupation: </label>
				<div class='fieldBoxLarge'>
				<select  style="width:200px" required='true' name='Balaji_fatherOcc' id='Balaji_fatherOcc'    tip="Please select your father's occupation."   title="Father's Occupation"   validate="validateSelect" caption="father's occupation"   onmouseover="showTipOnline('Please select your father occupation.',this);" onmouseout="hidetip();" ><option value='' selected>Select</option><option value='Service' >Service</option><option value='Self-Employed' >Self-Employed</option><option value='Retired' >Retired</option><option value='Other' >Other</option></select>
				<?php if(isset($Balaji_fatherOcc) && $Balaji_fatherOcc!=""){ ?>
			      <script>
				  var selObj = document.getElementById("Balaji_fatherOcc"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $Balaji_fatherOcc;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_fatherOcc_error'></div></div>
				</div>
				</div>
			      
			      <div class='additionalInfoRightCol'>
				<label>Father's Organization Name: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='Balaji_fatherOrgName' id='Balaji_fatherOrgName' tip="Enter your father's organization name."  validate="validateStr" title="Father's Organization Name" caption="father's organization name"   minlength="2"   maxlength="100"  value=''   />
				<?php if(isset($Balaji_fatherOrgName) && $Balaji_fatherOrgName!=""){ ?>
				  <script>
				      document.getElementById("Balaji_fatherOrgName").value = "<?php echo str_replace("\n", '\n', $Balaji_fatherOrgName );  ?>";
				      document.getElementById("Balaji_fatherOrgName").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_fatherOrgName_error'></div></div>
				</div>
				</div>
			      
			      
			</li>
			
			<li>
			  
			   <div class='additionalInfoLeftCol'>
				<label>Father's Mobile: </label>
				<div class='fieldBoxLarge'>
			      <input class="textboxLarge"  type='text' name='Balaji_fatherMobile' id='Balaji_fatherMobile'  validate="validateMobileInteger"   minlength="10"   maxlength="10"   caption="father's mobile no."  tip="Type in your father's 10-digit mobile number here."   value=''  />
			    <?php if(isset($Balaji_fatherMobile) && $Balaji_fatherMobile!=""){ ?>
					    <script>
						document.getElementById("Balaji_fatherMobile").value = "<?php echo str_replace("\n", '\n', $Balaji_fatherMobile );  ?>";
						document.getElementById("Balaji_fatherMobile").style.color = "";
					    </script>
					  <?php } ?>
			    <div style='display:none'><div class='errorMsg' id= 'Balaji_fatherMobile_error'></div></div>
			     </div>
			    </div>
			   
			    <div class='additionalInfoRightCol'>
				<label>Father's Residence Phone : </label>
				<div class='fieldBoxLarge'>
			      <input class="textboxLarge"  type='text' name='Balaji_fatherResiPhone' id='Balaji_fatherResiPhone'  validate="validateInteger"    caption="father's residence phone"   minlength="4"   maxlength="10"     tip="Type in your father's six digit, seven digit, or eight digit landline phone number here."   value=''  />
			    <?php if(isset($Balaji_fatherResiPhone) && $Balaji_fatherResiPhone!=""){ ?>
					    <script>
						document.getElementById("Balaji_fatherResiPhone").value = "<?php echo str_replace("\n", '\n', $Balaji_fatherResiPhone);  ?>";
						document.getElementById("Balaji_fatherResiPhone").style.color = "";
					    </script>
					  <?php } ?>
			    <div style='display:none'><div class='errorMsg' id= 'Balaji_fatherResiPhone_error'></div></div>
			     </div>
			    </div>
		      </li>
		      <li>
		     
			    <div class='additionalInfoLeftCol'>
				<label>Father's Office Phone : </label>
				<div class='fieldBoxLarge'>
			      <input class="textboxLarge"  type='text' name='Balaji_fatherOffPhone' id='Balaji_fatherOffPhone'  validate="validateInteger"    caption="father's office phone"   minlength="4"   maxlength="10"     tip="Type in your father's six digit, seven digit, or eight digit office landline phone number here."   value=''  />
			    <?php if(isset($Balaji_fatherOffPhone) && $Balaji_fatherOffPhone!=""){ ?>
					    <script>
						document.getElementById("Balaji_fatherOffPhone").value = "<?php echo str_replace("\n", '\n', $Balaji_fatherOffPhone);  ?>";
						document.getElementById("Balaji_fatherOffPhone").style.color = "";
					    </script>
					  <?php } ?>
			    <div style='display:none'><div class='errorMsg' id= 'Balaji_fatherOffPhone_error'></div></div>
			     </div>
			    </div>
			    
			    <div class='additionalInfoRightCol'>
				<label>Mother's Occupation: </label>
				<div class='fieldBoxLarge'>
				<select  style="width:200px"  required='true' name='Balaji_motherOcc' id='Balaji_motherOcc'    tip="Please select your mother's occupation."   title="Mother's Occupation"   validate="validateSelect"  caption="mother's occupation"   onmouseover="showTipOnline('Please select your mother occupation.',this);" onmouseout="hidetip();" ><option value='' selected>Select</option><option value='Service' >Service</option><option value='Self-Employed' >Self-Employed</option><option value='Retired' >Retired</option><option value='HouseWife' >HouseWife</option><option value='Other' >Other</option></select>
				<?php if(isset($Balaji_motherOcc) && $Balaji_motherOcc!=""){ ?>
			      <script>
				  var selObj = document.getElementById("Balaji_motherOcc"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $Balaji_motherOcc;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_motherOcc_error'></div></div>
				</div>
				</div>
			    
			</li>
		       <li>
				<div class='additionalInfoLeftCol'>
				<label>Family's annual Income: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='Balaji_annualIncome' id='Balaji_annualIncome'  validate="validateInteger"   required="true"   caption="annual income"   minlength="2"   maxlength="100"     tip="Please enter your family's combined annual income. Only numeric character allowed."   title="Family's annual Income"  value=''   />
				<?php if(isset($Balaji_annualIncome) && $Balaji_annualIncome!=""){ ?>
				  <script>
				      document.getElementById("Balaji_annualIncome").value = "<?php echo str_replace("\n", '\n', $Balaji_annualIncome );  ?>";
				      document.getElementById("Balaji_annualIncome").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_annualIncome_error'></div></div>
				</div>
				</div>
			  
			
				<div class='additionalInfoRightCol'>
				<label>Source of Income: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='Balaji_incomeSource' id='Balaji_incomeSource'  validate="validateStr"   required="true"   caption="source of income"   minlength="2"   maxlength="100"     tip="Please enter the sources of your family's combined income eg: Father's salary, mother's salary, father's business, agriculture income etc."   title="Sources of Income"  value=''   />
				<?php if(isset($Balaji_incomeSource) && $Balaji_incomeSource!=""){ ?>
				  <script>
				      document.getElementById("Balaji_incomeSource").value = "<?php echo str_replace("\n", '\n', $Balaji_incomeSource );  ?>";
				      document.getElementById("Balaji_incomeSource").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_incomeSource_error'></div></div>
				</div>
				</div>
			</li>
		       
		       <li>
			  <label>Father's Office Address</label>
		    </li>
                    <li>
                        
                        <div class="additionalInfoLeftCol">
			    <label>Street: </label>
			    <div class="fieldBoxLarge">
			    <input class="textboxLarge" type='text' name='Balaji_fatheroffStreet' id='Balaji_fatheroffStreet'  validate="validateStr"   caption="father's office Street Name or Area/Locality" minlength="2"   maxlength="50" class = "textboxLarge"   title="Street" tip="Enter your father's office street no."  value=''  />
			    <?php if(isset($Balaji_fatheroffStreet) && $Balaji_fatheroffStreet!=""){ ?>
					    <script>
						document.getElementById("Balaji_fatheroffStreet").value = "<?php echo str_replace("\n", '\n', $Balaji_fatheroffStreet );  ?>";
						document.getElementById("Balaji_fatheroffStreet").style.color = "";
					    </script>
					  <?php } ?>
			    <div style='display:none'><div class='errorMsg' id= 'Balaji_fatheroffStreet_error'></div></div>
			    </div>
                        </div>
                   
                        <div class="additionalInfoRightCol">
			    <label>Additional: </label>
			    <div class="fieldBoxLarge">
			    <input class="textboxLarge" type='text' name='Balaji_fatheroffCArea' id='Balaji_fatheroffCArea' validate="validateStr"  minlength="2"   maxlength="50"  caption="father's office Area Name or any other additional locality"   class = "textboxLarge"     title="Enter your father's office Area Name or any other additional locality" tip="Enter your father's office Area Name or any other additional locality."  value=''  />
			    <?php if(isset($Balaji_fatheroffCArea) && $Balaji_fatheroffCArea!=""){ ?>
					    <script>
						document.getElementById("Balaji_fatheroffCArea").value = "<?php echo str_replace("\n", '\n', $Balaji_fatheroffCArea );  ?>";
						document.getElementById("Balaji_fatheroffCArea").style.color = "";
					    </script>
					  <?php } ?>
			    <div style='display:none'><div class='errorMsg' id= 'Balaji_fatheroffCArea_error'></div></div>
			    </div>
                        </div>
                    </li>
		    
		    <li>
		
			<div class="additionalInfoLeftCol">
			      <label>State: </label>
			      <div class='fieldBoxLarge'>
				<select  style="width:200px"  name='Balaji_fatheroffState' id='Balaji_fatheroffState'    tip="Please select your state."   title="State"   validate="validateSelect"  onmouseover="showTipOnline('Please select state of your father office.',this);" onmouseout="hidetip();" ><option value='' selected>Select</option><option value='Andhra Pradesh' >Andhra Pradesh</option><option value='Arunachal Pradesh' >Arunachal Pradesh</option><option value='Assam' >Assam</option><option value='Bihar' >Bihar</option><option value='Chhattisgarh' >Chhattisgarh</option><option value='Goa' >Goa</option><option value='Gujrat' >Gujrat</option><option value='Haryana' >Haryana</option><option value='Himachal Pradesh' >Himachal Pradesh</option><option value='Jammu and Kashmir' >Jammu and Kashmir</option><option value='Jharkhand' >Jharkhand</option><option value='Karnataka' >Karnataka</option><option value='Kerala' >Kerala</option><option value='Madhya Pradesh' >Madhya Pradesh</option><option value='Maharashtra' >Maharashtra</option><option value='Manipur' >Manipur</option><option value='Meghalaya' >Meghalaya</option><option value='Mizoram' >Mizoram</option><option value='Nagaland' >Nagaland</option><option value='Odisha' >Odisha</option><option value='West Bengal' >West Bengal</option><option value='Punjab' >Punjab</option><option value='Rajasthan' >Rajasthan</option><option value='Sikkim' >Sikkim</option><option value='Tamil Nadu' >Tamil Nadu</option><option value='Telangana' >Telangana</option><option value='Tripura' >Tripura</option><option value='Uttar Pradesh' >Uttar Pradesh</option><option value='Uttarakhand' >Uttarakhand</option></select>
				<?php if(isset($Balaji_fatheroffState) && $Balaji_fatheroffState!=""){ ?>
			      <script>
				  var selObj = document.getElementById("Balaji_fatheroffState"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $Balaji_fatheroffState;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_fatheroffState_error'></div></div>
			  </div>
			  </div>
			
			<div class="additionalInfoRightCol">
			      <label>City: </label>
			      <div class="fieldBoxLarge">
			      <input type='text' class="textboxLarge" name='Balaji_fatheroffCity' id='Balaji_fatheroffCity'  validate="validateStr"  minlength="2"   maxlength="25"   caption="father's office city"  tip="Enter your father's office city name."   value='' />
			      <?php if(isset($Balaji_fatheroffCity) && $Balaji_fatheroffCity!=""){ ?>
					      <script>
						  document.getElementById("Balaji_fatheroffCity").value = "<?php echo str_replace("\n", '\n', $Balaji_fatheroffCity);  ?>";
						  document.getElementById("Balaji_fatheroffCity").style.color = "";
					      </script>
					    <?php } ?>
			      <div style='display:none'><div class='errorMsg' id= 'Balaji_fatheroffCity_error'></div></div>
			      </div>
			  </div>

                    </li>
                    
                    <li>
		
			<!--<div class="additionalInfoLeftCol">
			      <label>City: </label>
			      <div class="float_L">
			      <select name='Balaji_fatherOffCity' id='Balaji_fatherOffCity'  class="selectBoxLarge"   onmouseover="showTipOnline('Choose the city in which the office is located. If the city is not on the list, choose the nearest city here and write the name of your city in the text box corresponding to Additional field.',this);" onmouseout="hidetip();"   required="true"  validate="validateSelect"  caption = "City"  onChange = "fillState(this.value,'Balaji_fatherOffState');"/></select>
			      <div style='display:none'><div class='errorMsg' id= 'Balaji_fatherOffCity_error'></div></div>
			      </div>
			  </div>
			
			<div class="additionalInfoRightCol">
			      <label>State: </label>
			      <div class="fieldBoxLarge">
			      <input type='text' class="textboxLarge" name='Balaji_fatherOffState' id='Balaji_fatherOffState'  validate="validateStr"   required="true"   caption="State"   minlength="2"   maxlength="25"     tip="Please type State. If your fathers office is in India, please select the city and state will be populated."   value=''  readonly/>
			      <?php if(isset($Balaji_fatherOffState) && $Balaji_fatherOffState!=""){ ?>
					      <script>
						  document.getElementById("Balaji_fatherOffState").value = "<?php echo str_replace("\n", '\n', $Balaji_fatherOffState);  ?>";
						  document.getElementById("Balaji_fatherOffState").style.color = "";
					      </script>
					    <?php } ?>
			      <div style='display:none'><div class='errorMsg' id= 'Balaji_fatherOffState_error'></div></div>
			      </div>
			  </div>-->

                    </li>
                    
                    <li>
                        				
                        <div class="additionalInfoLeftCol">
			    <label>Postal code : </label>
			    <div class="fieldBoxSmall">
			    <input class="textboxLarge" type='text' name='Balaji_fatheroffPincode' id='Balaji_fatheroffPincode'  validate="validateInteger"   minlength="4"   maxlength="8"    caption="father's office pincode"   title="Enter Pincode" tip="Enter your father's office pincode."  value=''  />
			    <?php if(isset($Balaji_fatheroffPincode) && $Balaji_fatheroffPincode!=""){ ?>
					    <script>
						document.getElementById("Balaji_fatheroffPincode").value = "<?php echo str_replace("\n", '\n', $Balaji_fatheroffPincode );  ?>";
						document.getElementById("Balaji_fatheroffPincode").style.color = "";
					    </script>
					  <?php } ?>
			    <div style='display:none'><div class='errorMsg' id= 'Balaji_fatheroffPincode_error'></div></div>
			    </div>
                        </div>
			
			<div class="additionalInfoRightCol">
			    <label>Country: </label>
			    <div class="float_L">
			    <select class="selectBoxMedium" name='Balaji_fatheroffCountry' id='Balaji_fatheroffCountry'   onmouseover="showTipOnline('Choose the country in which your father office is located.',this);" onmouseout="hidetip();"  ><?php foreach($country_list as $countryItem) {
							$countryId = $countryItem["countryID"];
							$countryName = $countryItem["countryName"];
							if($countryId == 1) { continue; }
								if($countryName == $Balaji_fatheroffCountry) { $selected = "selected"; }
							else { $selected = ""; } ?>
						<option value="<?php echo $countryName; ?>" <?php echo $selected; ?> ><?php echo $countryName; ?></option>
						<?php } ?></select>
			    <div style='display:none'><div class='errorMsg' id= 'Balaji_fatheroffCountry_error'></div></div>
			    </div>
                        </div> 
                    </li>
		    
		    <li>
				<h3 class="upperCase">Course Preference</h3>
				</li>
				<li>
				<div class='additionalInfoLeftCol'>
				<label>Course Preference 1: </label>
				<div class='fieldBoxLarge'>
				<select  style="width:200px" onchange="checkForMultipleCourse(this);"  required='true' name='Balaji_coursePref1' id='Balaji_coursePref1'    tip="Please select your 1st course preference."   title="Course Preference 1"   validate="validateSelect"   required="true"   caption="1st course preference"   onmouseover="showTipOnline('Please select your 1st course preference.',this);" onmouseout="hidetip();" ><option value='' selected>Select</option><option value='PGDM offered by BIMHRD' >PGDM offered by BIMHRD</option><option value='PGDM (Marketing and Finance) offered by BIMHRD' >PGDM (Marketing and Finance) offered by BIMHRD</option><option value='PGDM (PM and HRD) offered by BIMHRD' >PGDM (PM and HRD) offered by BIMHRD</option><option value='PGDM (International Business) offered by BIIB' >PGDM (International Business) offered by BIIB</option><option value='PGDM (Marketing) offered by BIIB' >PGDM (Marketing) offered by BIIB</option><option value='PGDM (Finance) offered by BIIB' >PGDM (Finance) offered by BIIB</option><option value='PGDM (Telecom) offered by BITM' >PGDM (Telecom) offered by BITM</option><option value='PGDM (Telecom and Marketing) offered by BITM' >PGDM (Telecom and Marketing) offered by BITM</option><option value='PGDM (Marketing and Finance) offered by BITM' >PGDM (Marketing and Finance) offered by BITM</option><option value='PGDM offered by BIMM' >PGDM offered by BIMM</option><option value='PGDM (IT and Marketing) offered by BIMM' >PGDM (IT and Marketing) offered by BIMM</option><option value='PGDM (PM and HRD) offered by BIMM' >PGDM (PM and HRD) offered by BIMM</option><option value='PGDM (Executive Batch) offered by BIMM' >PGDM (Executive Batch) offered by BIMM</option></select>
				<?php if(isset($Balaji_coursePref1) && $Balaji_coursePref1!=""){ ?>
			      <script>
				  var selObj = document.getElementById("Balaji_coursePref1"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $Balaji_coursePref1;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_coursePref1_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Course Preference 2: </label>
				<div class='fieldBoxLarge'>
				<select  style="width:200px" onchange="checkForMultipleCourse(this);"  name='Balaji_coursePref2' id='Balaji_coursePref2'    tip="Please select your 2nd course preference."   title="Course Preference 2"  onmouseover="showTipOnline('Please select your 2nd course preference.',this);" onmouseout="hidetip();" ><option value='' selected>Select</option><option value='PGDM offered by BIMHRD' >PGDM offered by BIMHRD</option><option value='PGDM (Marketing and Finance) offered by BIMHRD' >PGDM (Marketing and Finance) offered by BIMHRD</option><option value='PGDM (PM and HRD) offered by BIMHRD' >PGDM (PM and HRD) offered by BIMHRD</option><option value='PGDM (International Business) offered by BIIB' >PGDM (International Business) offered by BIIB</option><option value='PGDM (Marketing) offered by BIIB' >PGDM (Marketing) offered by BIIB</option><option value='PGDM (Finance) offered by BIIB' >PGDM (Finance) offered by BIIB</option><option value='PGDM (Telecom) offered by BITM' >PGDM (Telecom) offered by BITM</option><option value='PGDM (Telecom and Marketing) offered by BITM' >PGDM (Telecom and Marketing) offered by BITM</option><option value='PGDM (Marketing and Finance) offered by BITM' >PGDM (Marketing and Finance) offered by BITM</option><option value='PGDM offered by BIMM' >PGDM offered by BIMM</option><option value='PGDM (IT and Marketing) offered by BIMM' >PGDM (IT and Marketing) offered by BIMM</option><option value='PGDM (PM and HRD) offered by BIMM' >PGDM (PM and HRD) offered by BIMM</option><option value='PGDM (Executive Batch) offered by BIMM' >PGDM (Executive Batch) offered by BIMM</option></select>
				<?php if(isset($Balaji_coursePref2) && $Balaji_coursePref2!=""){ ?>
			      <script>
				  var selObj = document.getElementById("Balaji_coursePref2"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $Balaji_coursePref2;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_coursePref2_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Course Preference 3: </label>
				<div class='fieldBoxLarge'>
				<select  style="width:200px" onchange="checkForMultipleCourse(this);"   name='Balaji_coursePref3' id='Balaji_coursePref3'    tip="Please select your 3rd course preference."   title="Course Preference 3"   onmouseover="showTipOnline('Please select your 3rd course preference.',this);" onmouseout="hidetip();" ><option value='' selected>Select</option><option value='PGDM offered by BIMHRD' >PGDM offered by BIMHRD</option><option value='PGDM (Marketing and Finance) offered by BIMHRD' >PGDM (Marketing and Finance) offered by BIMHRD</option><option value='PGDM (PM and HRD) offered by BIMHRD' >PGDM (PM and HRD) offered by BIMHRD</option><option value='PGDM (International Business) offered by BIIB' >PGDM (International Business) offered by BIIB</option><option value='PGDM (Marketing) offered by BIIB' >PGDM (Marketing) offered by BIIB</option><option value='PGDM (Finance) offered by BIIB' >PGDM (Finance) offered by BIIB</option><option value='PGDM (Telecom) offered by BITM' >PGDM (Telecom) offered by BITM</option><option value='PGDM (Telecom and Marketing) offered by BITM' >PGDM (Telecom and Marketing) offered by BITM</option><option value='PGDM (Marketing and Finance) offered by BITM' >PGDM (Marketing and Finance) offered by BITM</option><option value='PGDM offered by BIMM' >PGDM offered by BIMM</option><option value='PGDM (IT and Marketing) offered by BIMM' >PGDM (IT and Marketing) offered by BIMM</option><option value='PGDM (PM and HRD) offered by BIMM' >PGDM (PM and HRD) offered by BIMM</option><option value='PGDM (Executive Batch) offered by BIMM' >PGDM (Executive Batch) offered by BIMM</option></select>
				<?php if(isset($Balaji_coursePref3) && $Balaji_coursePref3!=""){ ?>
			      <script>
				  var selObj = document.getElementById("Balaji_coursePref3"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $Balaji_coursePref3;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_coursePref3_error'></div></div>
				</div>
				</div>
		
				<div class='additionalInfoRightCol'>
				<label>Course Preference 4: </label>
				<div class='fieldBoxLarge'>
				<select  style="width:200px" onchange="checkForMultipleCourse(this);" name='Balaji_coursePref4' id='Balaji_coursePref4'    tip="Please select your 4th course preference."   title="Course Preference 4"  onmouseover="showTipOnline('Please select your 4th course preference.',this);" onmouseout="hidetip();" ><option value='' selected>Select</option><option value='PGDM offered by BIMHRD' >PGDM offered by BIMHRD</option><option value='PGDM (Marketing and Finance) offered by BIMHRD' >PGDM (Marketing and Finance) offered by BIMHRD</option><option value='PGDM (PM and HRD) offered by BIMHRD' >PGDM (PM and HRD) offered by BIMHRD</option><option value='PGDM (International Business) offered by BIIB' >PGDM (International Business) offered by BIIB</option><option value='PGDM (Marketing) offered by BIIB' >PGDM (Marketing) offered by BIIB</option><option value='PGDM (Finance) offered by BIIB' >PGDM (Finance) offered by BIIB</option><option value='PGDM (Telecom) offered by BITM' >PGDM (Telecom) offered by BITM</option><option value='PGDM (Telecom and Marketing) offered by BITM' >PGDM (Telecom and Marketing) offered by BITM</option><option value='PGDM (Marketing and Finance) offered by BITM' >PGDM (Marketing and Finance) offered by BITM</option><option value='PGDM offered by BIMM' >PGDM offered by BIMM</option><option value='PGDM (IT and Marketing) offered by BIMM' >PGDM (IT and Marketing) offered by BIMM</option><option value='PGDM (PM and HRD) offered by BIMM' >PGDM (PM and HRD) offered by BIMM</option><option value='PGDM (Executive Batch) offered by BIMM' >PGDM (Executive Batch) offered by BIMM</option></select>
				<?php if(isset($Balaji_coursePref4) && $Balaji_coursePref4!=""){ ?>
			      <script>
				  var selObj = document.getElementById("Balaji_coursePref4"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $Balaji_coursePref4;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_coursePref4_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Course Preference 5: </label>
				<div class='fieldBoxLarge'>
				<select  style="width:200px" onchange="checkForMultipleCourse(this);"  name='Balaji_coursePref5' id='Balaji_coursePref5'    tip="Please select your 5th course preference."   title="Course Preference 5"   onmouseover="showTipOnline('Please select your 5th course preference.',this);" onmouseout="hidetip();" ><option value='' selected>Select</option><option value='PGDM offered by BIMHRD' >PGDM offered by BIMHRD</option><option value='PGDM (Marketing and Finance) offered by BIMHRD' >PGDM (Marketing and Finance) offered by BIMHRD</option><option value='PGDM (PM and HRD) offered by BIMHRD' >PGDM (PM and HRD) offered by BIMHRD</option><option value='PGDM (International Business) offered by BIIB' >PGDM (International Business) offered by BIIB</option><option value='PGDM (Marketing) offered by BIIB' >PGDM (Marketing) offered by BIIB</option><option value='PGDM (Finance) offered by BIIB' >PGDM (Finance) offered by BIIB</option><option value='PGDM (Telecom) offered by BITM' >PGDM (Telecom) offered by BITM</option><option value='PGDM (Telecom and Marketing) offered by BITM' >PGDM (Telecom and Marketing) offered by BITM</option><option value='PGDM (Marketing and Finance) offered by BITM' >PGDM (Marketing and Finance) offered by BITM</option><option value='PGDM offered by BIMM' >PGDM offered by BIMM</option><option value='PGDM (IT and Marketing) offered by BIMM' >PGDM (IT and Marketing) offered by BIMM</option><option value='PGDM (PM and HRD) offered by BIMM' >PGDM (PM and HRD) offered by BIMM</option><option value='PGDM (Executive Batch) offered by BIMM' >PGDM (Executive Batch) offered by BIMM</option></select>
				<?php if(isset($Balaji_coursePref5) && $Balaji_coursePref5!=""){ ?>
			      <script>
				  var selObj = document.getElementById("Balaji_coursePref5"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $Balaji_coursePref5;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_coursePref5_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Course Preference 6: </label>
				<div class='fieldBoxLarge'>
				<select  style="width:200px" onchange="checkForMultipleCourse(this);"  name='Balaji_coursePref6' id='Balaji_coursePref6'    tip="Please select your 6th course preference."   title="Course Preference 6"  onmouseover="showTipOnline('Please select your 6th course preference.',this);" onmouseout="hidetip();" ><option value='' selected>Select</option><option value='PGDM offered by BIMHRD' >PGDM offered by BIMHRD</option><option value='PGDM (Marketing and Finance) offered by BIMHRD' >PGDM (Marketing and Finance) offered by BIMHRD</option><option value='PGDM (PM and HRD) offered by BIMHRD' >PGDM (PM and HRD) offered by BIMHRD</option><option value='PGDM (International Business) offered by BIIB' >PGDM (International Business) offered by BIIB</option><option value='PGDM (Marketing) offered by BIIB' >PGDM (Marketing) offered by BIIB</option><option value='PGDM (Finance) offered by BIIB' >PGDM (Finance) offered by BIIB</option><option value='PGDM (Telecom) offered by BITM' >PGDM (Telecom) offered by BITM</option><option value='PGDM (Telecom and Marketing) offered by BITM' >PGDM (Telecom and Marketing) offered by BITM</option><option value='PGDM (Marketing and Finance) offered by BITM' >PGDM (Marketing and Finance) offered by BITM</option><option value='PGDM offered by BIMM' >PGDM offered by BIMM</option><option value='PGDM (IT and Marketing) offered by BIMM' >PGDM (IT and Marketing) offered by BIMM</option><option value='PGDM (PM and HRD) offered by BIMM' >PGDM (PM and HRD) offered by BIMM</option><option value='PGDM (Executive Batch) offered by BIMM' >PGDM (Executive Batch) offered by BIMM</option></select>
				<?php if(isset($Balaji_coursePref6) && $Balaji_coursePref6!=""){ ?>
			      <script>
				  var selObj = document.getElementById("Balaji_coursePref6"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $Balaji_coursePref6;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_coursePref6_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Course Preference 7: </label>
				<div class='fieldBoxLarge'>
				<select  style="width:200px" onchange="checkForMultipleCourse(this);"  name='Balaji_coursePref7' id='Balaji_coursePref7'    tip="Please select your 7th course preference."   title="Course Preference 7"  onmouseover="showTipOnline('Please select your 7th course preference.',this);" onmouseout="hidetip();" ><option value='' selected>Select</option><option value='PGDM offered by BIMHRD' >PGDM offered by BIMHRD</option><option value='PGDM (Marketing and Finance) offered by BIMHRD' >PGDM (Marketing and Finance) offered by BIMHRD</option><option value='PGDM (PM and HRD) offered by BIMHRD' >PGDM (PM and HRD) offered by BIMHRD</option><option value='PGDM (International Business) offered by BIIB' >PGDM (International Business) offered by BIIB</option><option value='PGDM (Marketing) offered by BIIB' >PGDM (Marketing) offered by BIIB</option><option value='PGDM (Finance) offered by BIIB' >PGDM (Finance) offered by BIIB</option><option value='PGDM (Telecom) offered by BITM' >PGDM (Telecom) offered by BITM</option><option value='PGDM (Telecom and Marketing) offered by BITM' >PGDM (Telecom and Marketing) offered by BITM</option><option value='PGDM (Marketing and Finance) offered by BITM' >PGDM (Marketing and Finance) offered by BITM</option><option value='PGDM offered by BIMM' >PGDM offered by BIMM</option><option value='PGDM (IT and Marketing) offered by BIMM' >PGDM (IT and Marketing) offered by BIMM</option><option value='PGDM (PM and HRD) offered by BIMM' >PGDM (PM and HRD) offered by BIMM</option><option value='PGDM (Executive Batch) offered by BIMM' >PGDM (Executive Batch) offered by BIMM</option></select>
				<?php if(isset($Balaji_coursePref7) && $Balaji_coursePref7!=""){ ?>
			      <script>
				  var selObj = document.getElementById("Balaji_coursePref7"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $Balaji_coursePref7;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_coursePref7_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Course Preference 8: </label>
				<div class='fieldBoxLarge'>
				<select  style="width:200px" onchange="checkForMultipleCourse(this);"  name='Balaji_coursePref8' id='Balaji_coursePref8'    tip="Please select your 8th course preference."   title="Course Preference 8"   onmouseover="showTipOnline('Please select your 8th course preference.',this);" onmouseout="hidetip();" ><option value='' selected>Select</option><option value='PGDM offered by BIMHRD' >PGDM offered by BIMHRD</option><option value='PGDM (Marketing and Finance) offered by BIMHRD' >PGDM (Marketing and Finance) offered by BIMHRD</option><option value='PGDM (PM and HRD) offered by BIMHRD' >PGDM (PM and HRD) offered by BIMHRD</option><option value='PGDM (International Business) offered by BIIB' >PGDM (International Business) offered by BIIB</option><option value='PGDM (Marketing) offered by BIIB' >PGDM (Marketing) offered by BIIB</option><option value='PGDM (Finance) offered by BIIB' >PGDM (Finance) offered by BIIB</option><option value='PGDM (Telecom) offered by BITM' >PGDM (Telecom) offered by BITM</option><option value='PGDM (Telecom and Marketing) offered by BITM' >PGDM (Telecom and Marketing) offered by BITM</option><option value='PGDM (Marketing and Finance) offered by BITM' >PGDM (Marketing and Finance) offered by BITM</option><option value='PGDM offered by BIMM' >PGDM offered by BIMM</option><option value='PGDM (IT and Marketing) offered by BIMM' >PGDM (IT and Marketing) offered by BIMM</option><option value='PGDM (PM and HRD) offered by BIMM' >PGDM (PM and HRD) offered by BIMM</option><option value='PGDM (Executive Batch) offered by BIMM' >PGDM (Executive Batch) offered by BIMM</option></select>
				<?php if(isset($Balaji_coursePref8) && $Balaji_coursePref8!=""){ ?>
			      <script>
				  var selObj = document.getElementById("Balaji_coursePref8"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $Balaji_coursePref8;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_coursePref8_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Course Preference 9: </label>
				<div class='fieldBoxLarge'>
				<select  style="width:200px" onchange="checkForMultipleCourse(this);"   name='Balaji_coursePref9' id='Balaji_coursePref9'    tip="Please select your 9th course preference."   title="Course Preference 9"  onmouseover="showTipOnline('Please select your 9th course preference.',this);" onmouseout="hidetip();" ><option value='' selected>Select</option><option value='PGDM offered by BIMHRD' >PGDM offered by BIMHRD</option><option value='PGDM (Marketing and Finance) offered by BIMHRD' >PGDM (Marketing and Finance) offered by BIMHRD</option><option value='PGDM (PM and HRD) offered by BIMHRD' >PGDM (PM and HRD) offered by BIMHRD</option><option value='PGDM (International Business) offered by BIIB' >PGDM (International Business) offered by BIIB</option><option value='PGDM (Marketing) offered by BIIB' >PGDM (Marketing) offered by BIIB</option><option value='PGDM (Finance) offered by BIIB' >PGDM (Finance) offered by BIIB</option><option value='PGDM (Telecom) offered by BITM' >PGDM (Telecom) offered by BITM</option><option value='PGDM (Telecom and Marketing) offered by BITM' >PGDM (Telecom and Marketing) offered by BITM</option><option value='PGDM (Marketing and Finance) offered by BITM' >PGDM (Marketing and Finance) offered by BITM</option><option value='PGDM offered by BIMM' >PGDM offered by BIMM</option><option value='PGDM (IT and Marketing) offered by BIMM' >PGDM (IT and Marketing) offered by BIMM</option><option value='PGDM (PM and HRD) offered by BIMM' >PGDM (PM and HRD) offered by BIMM</option><option value='PGDM (Executive Batch) offered by BIMM' >PGDM (Executive Batch) offered by BIMM</option></select>
				<?php if(isset($Balaji_coursePref9) && $Balaji_coursePref9!=""){ ?>
			      <script>
				  var selObj = document.getElementById("Balaji_coursePref9"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $Balaji_coursePref9;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_coursePref9_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Course Preference 10: </label>
				<div class='fieldBoxLarge'>
				<select  style="width:200px" onchange="checkForMultipleCourse(this);"  name='Balaji_coursePref10' id='Balaji_coursePref10'    tip="Please select your 10th course preference."   title="Course Preference 10"  onmouseover="showTipOnline('Please select your 10th course preference.',this);" onmouseout="hidetip();" ><option value='' selected>Select</option><option value='PGDM offered by BIMHRD' >PGDM offered by BIMHRD</option><option value='PGDM (Marketing and Finance) offered by BIMHRD' >PGDM (Marketing and Finance) offered by BIMHRD</option><option value='PGDM (PM and HRD) offered by BIMHRD' >PGDM (PM and HRD) offered by BIMHRD</option><option value='PGDM (International Business) offered by BIIB' >PGDM (International Business) offered by BIIB</option><option value='PGDM (Marketing) offered by BIIB' >PGDM (Marketing) offered by BIIB</option><option value='PGDM (Finance) offered by BIIB' >PGDM (Finance) offered by BIIB</option><option value='PGDM (Telecom) offered by BITM' >PGDM (Telecom) offered by BITM</option><option value='PGDM (Telecom and Marketing) offered by BITM' >PGDM (Telecom and Marketing) offered by BITM</option><option value='PGDM (Marketing and Finance) offered by BITM' >PGDM (Marketing and Finance) offered by BITM</option><option value='PGDM offered by BIMM' >PGDM offered by BIMM</option><option value='PGDM (IT and Marketing) offered by BIMM' >PGDM (IT and Marketing) offered by BIMM</option><option value='PGDM (PM and HRD) offered by BIMM' >PGDM (PM and HRD) offered by BIMM</option><option value='PGDM (Executive Batch) offered by BIMM' >PGDM (Executive Batch) offered by BIMM</option></select>
				<?php if(isset($Balaji_coursePref10) && $Balaji_coursePref10!=""){ ?>
			      <script>
				  var selObj = document.getElementById("Balaji_coursePref10"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $Balaji_coursePref10;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_coursePref10_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Course Preference 11: </label>
				<div class='fieldBoxLarge'>
				<select  style="width:200px" onchange="checkForMultipleCourse(this);"   name='Balaji_coursePref11' id='Balaji_coursePref11'    tip="Please select your 11th course preference."   title="Course Preference 11"   onmouseover="showTipOnline('Please select your 11th course preference.',this);" onmouseout="hidetip();" ><option value='' selected>Select</option><option value='PGDM offered by BIMHRD' >PGDM offered by BIMHRD</option><option value='PGDM (Marketing and Finance) offered by BIMHRD' >PGDM (Marketing and Finance) offered by BIMHRD</option><option value='PGDM (PM and HRD) offered by BIMHRD' >PGDM (PM and HRD) offered by BIMHRD</option><option value='PGDM (International Business) offered by BIIB' >PGDM (International Business) offered by BIIB</option><option value='PGDM (Marketing) offered by BIIB' >PGDM (Marketing) offered by BIIB</option><option value='PGDM (Finance) offered by BIIB' >PGDM (Finance) offered by BIIB</option><option value='PGDM (Telecom) offered by BITM' >PGDM (Telecom) offered by BITM</option><option value='PGDM (Telecom and Marketing) offered by BITM' >PGDM (Telecom and Marketing) offered by BITM</option><option value='PGDM (Marketing and Finance) offered by BITM' >PGDM (Marketing and Finance) offered by BITM</option><option value='PGDM offered by BIMM' >PGDM offered by BIMM</option><option value='PGDM (IT and Marketing) offered by BIMM' >PGDM (IT and Marketing) offered by BIMM</option><option value='PGDM (PM and HRD) offered by BIMM' >PGDM (PM and HRD) offered by BIMM</option><option value='PGDM (Executive Batch) offered by BIMM' >PGDM (Executive Batch) offered by BIMM</option></select>
				<?php if(isset($Balaji_coursePref11) && $Balaji_coursePref11!=""){ ?>
			      <script>
				  var selObj = document.getElementById("Balaji_coursePref11"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $Balaji_coursePref11;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_coursePref11_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Course Preference 12: </label>
				<div class='fieldBoxLarge'>
				<select  style="width:200px" onchange="checkForMultipleCourse(this);"   name='Balaji_coursePref12' id='Balaji_coursePref12'    tip="Please select your 12th course preference."   title="Course Preference 12"  onmouseover="showTipOnline('Please select your 12th course preference.',this);" onmouseout="hidetip();" ><option value='' selected>Select</option><option value='PGDM offered by BIMHRD' >PGDM offered by BIMHRD</option><option value='PGDM (Marketing and Finance) offered by BIMHRD' >PGDM (Marketing and Finance) offered by BIMHRD</option><option value='PGDM (PM and HRD) offered by BIMHRD' >PGDM (PM and HRD) offered by BIMHRD</option><option value='PGDM (International Business) offered by BIIB' >PGDM (International Business) offered by BIIB</option><option value='PGDM (Marketing) offered by BIIB' >PGDM (Marketing) offered by BIIB</option><option value='PGDM (Finance) offered by BIIB' >PGDM (Finance) offered by BIIB</option><option value='PGDM (Telecom) offered by BITM' >PGDM (Telecom) offered by BITM</option><option value='PGDM (Telecom and Marketing) offered by BITM' >PGDM (Telecom and Marketing) offered by BITM</option><option value='PGDM (Marketing and Finance) offered by BITM' >PGDM (Marketing and Finance) offered by BITM</option><option value='PGDM offered by BIMM' >PGDM offered by BIMM</option><option value='PGDM (IT and Marketing) offered by BIMM' >PGDM (IT and Marketing) offered by BIMM</option><option value='PGDM (PM and HRD) offered by BIMM' >PGDM (PM and HRD) offered by BIMM</option><option value='PGDM (Executive Batch) offered by BIMM' >PGDM (Executive Batch) offered by BIMM</option></select>
				<?php if(isset($Balaji_coursePref12) && $Balaji_coursePref12!=""){ ?>
			      <script>
				  var selObj = document.getElementById("Balaji_coursePref12"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $Balaji_coursePref12;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_coursePref12_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Course Preference 13: </label>
				<div class='fieldBoxLarge'>
				<select  style="width:200px" onchange="checkForMultipleCourse(this);"   name='Balaji_coursePref13' id='Balaji_coursePref13'    tip="Please select your 13th course preference."   title="Course Preference 13"  onmouseover="showTipOnline('Please select your 13th course preference.',this);" onmouseout="hidetip();" ><option value='' selected>Select</option><option value='PGDM offered by BIMHRD' >PGDM offered by BIMHRD</option><option value='PGDM (Marketing and Finance) offered by BIMHRD' >PGDM (Marketing and Finance) offered by BIMHRD</option><option value='PGDM (PM and HRD) offered by BIMHRD' >PGDM (PM and HRD) offered by BIMHRD</option><option value='PGDM (International Business) offered by BIIB' >PGDM (International Business) offered by BIIB</option><option value='PGDM (Marketing) offered by BIIB' >PGDM (Marketing) offered by BIIB</option><option value='PGDM (Finance) offered by BIIB' >PGDM (Finance) offered by BIIB</option><option value='PGDM (Telecom) offered by BITM' >PGDM (Telecom) offered by BITM</option><option value='PGDM (Telecom and Marketing) offered by BITM' >PGDM (Telecom and Marketing) offered by BITM</option><option value='PGDM (Marketing and Finance) offered by BITM' >PGDM (Marketing and Finance) offered by BITM</option><option value='PGDM offered by BIMM' >PGDM offered by BIMM</option><option value='PGDM (IT and Marketing) offered by BIMM' >PGDM (IT and Marketing) offered by BIMM</option><option value='PGDM (PM and HRD) offered by BIMM' >PGDM (PM and HRD) offered by BIMM</option><option value='PGDM (Executive Batch) offered by BIMM' >PGDM (Executive Batch) offered by BIMM</option></select>
				<?php if(isset($Balaji_coursePref13) && $Balaji_coursePref13!=""){ ?>
			      <script>
				  var selObj = document.getElementById("Balaji_coursePref13"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $Balaji_coursePref13;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_coursePref13_error'></div></div>
				</div>
				</div>
			</li>
			
			<li>
			  <h3 class=upperCase'>Centres for group discussion and personal interview</h3>
			  <p>While we are keen to have the admission process in all the centres mentioned below and have been having many of the centres for many years in the past, we reserve the right to cancel any of the centres depending upon the contingencies.</br><br/>

However, it may be noted that, the hall ticket issued by us to any centre is valid to all the centres and the students who may need a change of centre due to any reason can automatically go to any other centre by just informing us through an e-mail/fax and without expecting any response to the same. Changes in dates, if any, for the Group Discussion, Essay writing and Interviews will be available on our website on as required basis. Students are therefore advised to see the website regularly.</p>
			  
			</li>
			
			<li>
				<h3 class=upperCase'>GD & PI Centre Preference</h3>
				<label style='font-weight:normal'>Centre Preference 1: </label>
				<div class='fieldBoxLarge'>
				<select name='preferredGDPILocation' id='preferredGDPILocation' onmouseover="showTipOnline('Select your 1st preferred GD/PI location.',this);" onmouseout='hidetip();'  validate="validateSelect"  minlength="1"   maxlength="1500"  required="true" caption="1st GD/PI location" onchange="checkValidCampusPreference(1);" >
				<option value=''>Select</option>
				<?php foreach($gdpiLocations as $gdpiLocation): ?>
						<option value="<?php echo $gdpiLocation['city_id']; ?>"><?php echo $gdpiLocation['city_name']; ?></option>
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
			
		<li>
                              
				<div class='additionalInfoLeftCol'>
				<label>Centre Preference 2: </label>
				<div class='fieldBoxLarge'>
				<select name='Balaji_GDPI2' id='Balaji_GDPI2'  onmouseover="showTipOnline('Select your 2nd preferred GD/PI location.',this);" onmouseout='hidetip();' onchange="checkValidCampusPreference(2);">
				<option value=''>Select</option>
				<?php foreach($gdpiLocations as $gdpiLocation): ?>
						<option value="<?php echo $gdpiLocation['city_name']; ?>"><?php echo $gdpiLocation['city_name']; ?></option>
				<?php endforeach; ?>
				</select>
				<?php if(isset($Balaji_GDPI2) && $Balaji_GDPI2!=""){ ?>
			      <script>
				  var selObj = document.getElementById("Balaji_GDPI2"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $Balaji_GDPI2;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_GDPI2_error'></div></div>
				</div>
				</div>

			</li>

			<li>

				<div class='additionalInfoLeftCol'>
				<label>Centre Preference 3: </label>
				<div class='fieldBoxLarge'>
				<select name='Balaji_GDPI3' id='Balaji_GDPI3'   onmouseover="showTipOnline('Select your 3rd preferred GD/PI location.',this);" onmouseout='hidetip();' onchange="checkValidCampusPreference(3);">
					<option value=''>Select</option>
					<?php foreach($gdpiLocations as $gdpiLocation): ?>
						<option value="<?php echo $gdpiLocation['city_name']; ?>"><?php echo $gdpiLocation['city_name']; ?></option>
					<?php endforeach; ?>
				</select>
				<?php if(isset($Balaji_GDPI3) && $Balaji_GDPI3!=""){ ?>
			      <script>
				  var selObj = document.getElementById("Balaji_GDPI3"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $Balaji_GDPI3;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_GDPI3_error'></div></div>
				</div>
				</div>

			</li>
			
			<li>
				<h3 class="upperCase">Source of info</h3>
			</li>
			
			<li>
				<div>
				<label style="font-weight:normal !important">Source Of Information: </label>
				<div class='fieldBoxLarge' style= "width:575px;">
				<input onClick="showOtherSourceFields('Newspaper',$j(this).prop('checked'));" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="the source of information"   name='Balaji_sourceInfo[]' id='Balaji_sourceInfo0'   value='Newspaper advertisement'  onmouseover="showTipOnline('Tick the appropriate source.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate source of information',this);" onmouseout="hidetip();" >Newspaper advertisement</span>
				<input onClick="showOtherSourceFields('Magazine',$j(this).prop('checked'));" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="the source of information"   name='Balaji_sourceInfo[]' id='Balaji_sourceInfo1'   value='Magazine'  onmouseover="showTipOnline('Tick the appropriate source of information.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate source of information',this);" onmouseout="hidetip();" >Magazine</span>
				<input type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="the source of information"   name='Balaji_sourceInfo[]' id='Balaji_sourceInfo2'   value='Internet'  onmouseover="showTipOnline('Tick the appropriate source.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate source',this);" onmouseout="hidetip();" >Internet</span>&nbsp;&nbsp;
				<input type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="the source of information"   name='Balaji_sourceInfo[]' id='Balaji_sourceInfo3'   value='Alumni'  onmouseover="showTipOnline('Tick the appropriate source of information.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate source of information',this);" onmouseout="hidetip();" >Alumni</span>&nbsp;&nbsp;
				<input type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="the source of information"   name='Balaji_sourceInfo[]' id='Balaji_sourceInfo4'   value='Current Students'  onmouseover="showTipOnline('Tick the appropriate source of information.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate source of information',this);" onmouseout="hidetip();" >Current Students</span>&nbsp;&nbsp;
				<input type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="the source of information"   name='Balaji_sourceInfo[]' id='Balaji_sourceInfo5'   value='Parental Reference'  onmouseover="showTipOnline('Tick the appropriate source of information.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate source of information',this);" onmouseout="hidetip();" >Parental Reference</span>&nbsp;&nbsp;
				<input onClick="showOtherSourceFields('CoachingClass',$j(this).prop('checked'));" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="the source of information"   name='Balaji_sourceInfo[]' id='Balaji_sourceInfo6'   value='Coaching Classes'  onmouseover="showTipOnline('Tick the appropriate source of information.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate source of information',this);" onmouseout="hidetip();" >Coaching Classes</span>&nbsp;&nbsp;
				<input onClick="showOtherSourceFields('Other',$j(this).prop('checked'));" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="the source of information"   name='Balaji_sourceInfo[]' id='Balaji_sourceInfo7'   value='Any other source'  onmouseover="showTipOnline('Tick the appropriate source of information.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate source of information',this);" onmouseout="hidetip();" >Any other source</span>&nbsp;&nbsp;

				<?php if(isset($Balaji_sourceInfo) && $Balaji_sourceInfo!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["Balaji_sourceInfo[]"];
				
				    var countCheckBoxes = objCheckBoxes.length; console.log(countCheckBoxes);
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$Balaji_sourceInfo);
						    for($x=0;$x<count($arr);$x++){ ?>
							  if(objCheckBoxes[i].value == "<?php echo $arr[$x];?>") {
								  objCheckBoxes[i].checked = true;
							  }
					      <?php
						    }
					      ?>
				    }
				</script>
			      <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_sourceInfo_error'></div></div>
				</div>
				</div>
			</li>
			
			 
			  
			<li id="Newspaper" <?=(isset($Balaji_nameOfNewspaper) && $Balaji_nameOfNewspaper!='')?'':'style="display:none"'?>>
				<div class='additionalInfoLeftCol'>
				<label >Name of Newspaper: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='Balaji_nameOfNewspaper' id='Balaji_nameOfNewspaper'  validate="validateStr"    caption="name of newspaper"   minlength="2"   maxlength="100"  tip="Specify the name of the newspaper."   title="Name of Newspaper"  value=''   />
				<?php if(isset($Balaji_nameOfNewspaper) && $Balaji_nameOfNewspaper!=""){ ?>
				  <script>
				      document.getElementById("Balaji_nameOfNewspaper").value = "<?php echo str_replace("\n", '\n', $Balaji_nameOfNewspaper );  ?>";
				      document.getElementById("Balaji_nameOfNewspaper").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_nameOfNewspaper_error'></div></div>
				</div>
				</div>
			</li>

			<li id="Magazine" <?=(isset($Balaji_nameOfMagazine) && $Balaji_nameOfMagazine!='')?'':'style="display:none"'?>>
				<div class='additionalInfoLeftCol'>
				<label>Name of Magazine: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='Balaji_nameOfMagazine' id='Balaji_nameOfMagazine'  validate="validateStr"  caption="name of the magazine"   minlength="2"   maxlength="100"  tip="Specify the name of the magazine."   title="Name of Magazine"  value=''   />
				<?php if(isset($Balaji_nameOfMagazine) && $Balaji_nameOfMagazine!=""){ ?>
				  <script>
				      document.getElementById("Balaji_nameOfMagazine").value = "<?php echo str_replace("\n", '\n', $Balaji_nameOfMagazine );  ?>";
				      document.getElementById("Balaji_nameOfMagazine").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_nameOfMagazine_error'></div></div>
				</div>
				</div>
			</li>

			<li id="Other" <?=(isset($Balaji_nameOfOther) && $Balaji_nameOfOther!='')?'':'style="display:none"'?>>
				<div class='additionalInfoLeftCol'>
				<label>Name of Other source: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='Balaji_nameOfOther' id='Balaji_nameOfOther'  validate="validateStr"    caption="name of the other source"   minlength="2"   maxlength="100"     tip="Specify the name of the other source."   title="Name of Other source"  value=''   />
				<?php if(isset($Balaji_nameOfOther) && $Balaji_nameOfOther!=""){ ?>
				  <script>
				      document.getElementById("Balaji_nameOfOther").value = "<?php echo str_replace("\n", '\n', $Balaji_nameOfOther );  ?>";
				      document.getElementById("Balaji_nameOfOther").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_nameOfOther_error'></div></div>
				</div>
				</div>
			</li>
			
			<li id="CoachingClass" <?=(isset($Balaji_nameOfCoachingClass) && $Balaji_nameOfCoachingClass!='')?'':'style="display:none"'?>>
				<div class='additionalInfoLeftCol'>
				<label>Name of Coaching Classes: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='Balaji_nameOfCoachingClass' id='Balaji_nameOfCoachingClass'  validate="validateStr"    caption="name of the coaching class"   minlength="2"   maxlength="100"     tip="Specify the name of the coaching class."   title="Name of Coaching Classes"  value=''   />
				<?php if(isset($Balaji_nameOfCoachingClass) && $Balaji_nameOfCoachingClass!=""){ ?>
				  <script>
				      document.getElementById("Balaji_nameOfCoachingClass").value = "<?php echo str_replace("\n", '\n', $Balaji_nameOfCoachingClass );  ?>";
				      document.getElementById("Balaji_nameOfCoachingClass").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_nameOfCoachingClass_error'></div></div>
				</div>
				</div>
			</li>
			
			<li>
				<div >
				<label style="font-weight:normal !important">Valid Proof of address: </label>
				<div class='fieldBoxLarge' style= "width:550px;">
				<input  type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="the valid proof of address"   name='Balaji_proofOfAdd[]' id='Balaji_proofOfAdd0'   value='Driving Licence'  onmouseover="showTipOnline('Tick the valid proof of address',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the valid proof of address',this);" onmouseout="hidetip();" >Driving Licence</span>&nbsp;&nbsp;
				<input  type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="the valid proof of address"   name='Balaji_proofOfAdd[]' id='Balaji_proofOfAdd1'   value='Passport'  onmouseover="showTipOnline('Tick the valid proof of address',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the valid proof of address',this);" onmouseout="hidetip();" >Passport</span>&nbsp;&nbsp;
				<input  type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="the valid proof of address"   name='Balaji_proofOfAdd[]' id='Balaji_proofOfAdd2'   value="Voter's Id"  onmouseover="showTipOnline('Tick the valid proof of address',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the valid proof of address',this);" onmouseout="hidetip();" >Voter's Id</span>&nbsp;&nbsp;
				<input  type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="the valid proof of address"   name='Balaji_proofOfAdd[]' id='Balaji_proofOfAdd3'   value='Aadhar Card'  onmouseover="showTipOnline('Tick the valid proof of address',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the valid proof of address',this);" onmouseout="hidetip();" >Aadhar Card</span>&nbsp;&nbsp;
				<input  type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="the valid proof of address"   name='Balaji_proofOfAdd[]' id='Balaji_proofOfAdd4'   value='Ration card'  onmouseover="showTipOnline('Tick the valid proof of address',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the valid proof of address',this);" onmouseout="hidetip();" >Ration card</span>&nbsp;&nbsp;
				<input  type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="the valid proof of address"   name='Balaji_proofOfAdd[]' id='Balaji_proofOfAdd5'   value='Electricity Bill'  onmouseover="showTipOnline('Tick the valid proof of address',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the valid proof of address',this);" onmouseout="hidetip();" >Electricity Bill</span>&nbsp;&nbsp;
				<input  type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="the valid proof of address"   name='Balaji_proofOfAdd[]' id='Balaji_proofOfAdd6'   value='Lic Bond'  onmouseover="showTipOnline('Tick the valid proof of address',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the valid proof of address',this);" onmouseout="hidetip();" >Lic Bond</span>&nbsp;&nbsp;
				<input  type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="the valid proof of address"   name='Balaji_proofOfAdd[]' id='Balaji_proofOfAdd7'   value='Any Other Govt. Authorised Documents'  onmouseover="showTipOnline('Tick the valid proof of address',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the valid proof of address',this);" onmouseout="hidetip();" >Any Other Govt. Authorised Documents</span>&nbsp;&nbsp;

				<?php if(isset($Balaji_proofOfAdd) && $Balaji_proofOfAdd!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["Balaji_proofOfAdd[]"];
				
				    var countCheckBoxes = objCheckBoxes.length; console.log(countCheckBoxes);
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$Balaji_proofOfAdd);
						    for($x=0;$x<count($arr);$x++){ ?>
							  if(objCheckBoxes[i].value == "<?php echo $arr[$x];?>") {
								  objCheckBoxes[i].checked = true;
							  }
					      <?php
						    }
					      ?>
				    }
				</script>
			      <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_proofOfAdd_error'></div></div>
				</div>
				</div>
			</li>
			
			<li>
				<div class='additionalInfoLeftCol'>
				<label> Industry Sponsored: </label>
				<div class='fieldBoxLarge'>
				<input type='radio'  validate="validateCheckedGroup"   required="true"   caption="yes or no"  name='Balaji_sponsored' id='Balaji_sponsored0'   value='No' title=" Industry Sponsored"   onmouseover="showTipOnline('Any Industry Sponsored? If yes then please select Yes.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Any Industry Sponsored? If yes then please select Yes.',this);" onmouseout="hidetip();" >No</span>&nbsp;&nbsp;
				<input type='radio'  validate="validateCheckedGroup"   required="true"   caption="yes or no"  name='Balaji_sponsored' id='Balaji_sponsored1'   value='Yes' title=" Industry Sponsored"   onmouseover="showTipOnline('Any Industry Sponsored? If yes then please select Yes.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Any Industry Sponsored? If yes then please select Yes.',this);" onmouseout="hidetip();" >Yes</span>&nbsp;&nbsp;
				<?php if(isset($Balaji_sponsored) && $Balaji_sponsored!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["Balaji_sponsored"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $Balaji_sponsored;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_sponsored_error'></div></div>
				</div>
				</div>
			</li>
			<li>
				<div class='additionalInfoLeftCol'>
				<label> Management Quota: </label>
				<div class='fieldBoxLarge'>
				<input type='radio'  validate="validateCheckedGroup"   required="true"   caption="yes or no"  name='Balaji_mgtQuota' id='Balaji_mgtQuota0'   value='No' title=" Management Quota"   onmouseover="showTipOnline('Having any Mangement Quota? If yes then please select Yes.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Having any Mangement Quota? If yes then please select Yes.',this);" onmouseout="hidetip();" >No</span>&nbsp;&nbsp;
				<input type='radio'  validate="validateCheckedGroup"   required="true"   caption="yes or no"  name='Balaji_mgtQuota' id='Balaji_mgtQuota1'   value='Yes' title=" Management Quota"   onmouseover="showTipOnline('Any Mangement Quota? If yes then please select Yes.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Any Mangement Quota? If yes then please select Yes.',this);" onmouseout="hidetip();" >Yes</span>&nbsp;&nbsp;
				<?php if(isset($Balaji_mgtQuota) && $Balaji_mgtQuota!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["Balaji_mgtQuota"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $Balaji_mgtQuota;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_mgtQuota_error'></div></div>
				</div>
				</div>
			</li>
			
			<li>
				<div class='additionalInfoLeftCol'>
				<label>Hobbies and Interest: </label>
				<div class='fieldBoxLarge'>
				<textarea name='Balaji_hobby' id='Balaji_hobby'  validate="validateStr"    caption="Hobbies and Interest"   minlength="10"   maxlength="1000"   style="width:618px; height:74px; padding:5px"   tip="Enter your hobbies and interests. If you DO NOT have hobbies or interests, just enter <b>NA</b>."   title="Hobbies and Interest"    allowNA = 'true' ></textarea>
				<?php if(isset($Balaji_hobby) && $Balaji_hobby!=""){ ?>
				  <script>
				      document.getElementById("Balaji_hobby").value = "<?php echo str_replace("\n", '\n', $Balaji_hobby );  ?>";
				      document.getElementById("Balaji_hobby").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_hobby_error'></div></div>
				</div>
				</div>
			</li>
			<?php endif; ?>
						<li>
				<h3 class="upperCase">Entrance Exams Details</h3>
				<p>The candidate can submit the application form without the CAT/MAT/CMAT scores.They can submit the print copy of their score card when the results are declared, as we will declare the merit list after GD/PI which will be held after the declaration of CAT results.It may be noted that we are not going to do our short listing based on the CAT/MAT/CMAT score alone, although weightage is allocated for the same.</p>
				</li>
				<li>
				<div class='additionalInfoLeftCol' style="width:560px;">
				<label>TESTS: </label>
				<div class='fieldBoxLarge' style= "width:249px;">
				<input onClick="checkTestScore(this);" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="the tests"   name='Balaji_testNames[]' id='Balaji_testNames0'   value='CAT'  onmouseover="showTipOnline('Tick the appropriate box and provide test date and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test date, percentile, regn no. and center code (if available)',this);" onmouseout="hidetip();" >CAT</span>&nbsp;&nbsp;
				<input onClick="checkTestScore(this);" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="the tests"   name='Balaji_testNames[]' id='Balaji_testNames1'   value='MAT'     onmouseover="showTipOnline('Tick the appropriate box and provide test date and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test date and score (if available)',this);" onmouseout="hidetip();" >MAT</span>&nbsp;&nbsp;
				<input onClick="checkTestScore(this);" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="the tests"   name='Balaji_testNames[]' id='Balaji_testNames2'   value='CMAT'     onmouseover="showTipOnline('Tick the appropriate box and provide test date and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test date and score (if available)',this);" onmouseout="hidetip();" >CMAT</span>&nbsp;&nbsp;
 <input onClick="checkTestScore(this);" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="the tests"   name='Balaji_testNames[]' id='Balaji_testNames3'   value='XAT'     onmouseover="showTipOnline('Tick the appropriate box and provide test date and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test date and score (if available)',this);" onmouseout="hidetip();" >XAT</span>&nbsp;&nbsp;

				<?php if(isset($Balaji_testNames) && $Balaji_testNames!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["Balaji_testNames[]"];
				
				    var countCheckBoxes = objCheckBoxes.length; console.log(countCheckBoxes);
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$Balaji_testNames);
						    for($x=0;$x<count($arr);$x++){ ?>
							  if(objCheckBoxes[i].value == "<?php echo $arr[$x];?>") {
								  objCheckBoxes[i].checked = true;
							  }
					      <?php
						    }
					      ?>
				    }
				</script>
			      <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_testNames_error'></div></div>
				</div>
				</div>
			</li>
				
			<li id="cat" style="display:none">
				<div class='additionalInfoLeftCol'>
				<label>CAT Test Center Code: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='Balaji_CATCenterCode' id='Balaji_CATCenterCode'  validate="validateStr"    caption="center code"   minlength="1"   maxlength="100"     tip="Enter your CAT test center code."   title="CAT Test Center Code"  value=''   />
				<?php if(isset($Balaji_CATCenterCode) && $Balaji_CATCenterCode!=""){ ?>
				  <script>
				      document.getElementById("Balaji_CATCenterCode").value = "<?php echo str_replace("\n", '\n', $Balaji_CATCenterCode );  ?>";
				      document.getElementById("Balaji_CATCenterCode").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_CATCenterCode_error'></div></div>
				</div>
				</div>
		
				<div class='additionalInfoRightCol'>
				<label>CAT Regn. No.: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='catRollNumberAdditional' id='catRollNumberAdditional'   validate="validateStr"   caption="roll number"   minlength="2"   maxlength="50"        tip="Mention your roll number for the exam. If you have not appeared for this examination enter NA."   value=''  allowNA="true" />
				<?php if(isset($catRollNumberAdditional) && $catRollNumberAdditional!=""){ ?>
				  <script>
				      document.getElementById("catRollNumberAdditional").value = "<?php echo str_replace("\n", '\n', $catRollNumberAdditional );  ?>";
				      document.getElementById("catRollNumberAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'catRollNumberAdditional_error'></div></div>
				</div>
				</div>
			</li>

			<li id="cat1" style="display:none">
				<div class='additionalInfoLeftCol'>
				<label>CAT Percentile: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='catPercentileAdditional' id='catPercentileAdditional'   required="true"        validate="validateFloat"    caption="Percentile"   minlength="1"   maxlength="5"      tip="Mention your percentile in the exam. If you don't know your percentile, enter NA."   value=''   allowNA="true"  />
				<?php if(isset($catPercentileAdditional) && $catPercentileAdditional!=""){ ?>
				  <script>
				      document.getElementById("catPercentileAdditional").value = "<?php echo str_replace("\n", '\n', $catPercentileAdditional );  ?>";
				      document.getElementById("catPercentileAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'catPercentileAdditional_error'></div></div>
				</div>
				</div>
		      
			</li>

			<li id="mat" style="display:none">
				<div class='additionalInfoLeftCol'>
				<label>MAT Roll Number: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='matRollNumberAdditional' id='matRollNumberAdditional'   validate="validateStr"   caption="roll number"   minlength="2"   maxlength="50"       tip="Mention your roll number for the exam. If you have not appeared for this examination enter NA."   value=''   allowNA="true"  />
				<?php if(isset($matRollNumberAdditional) && $matRollNumberAdditional!=""){ ?>
				  <script>
				      document.getElementById("matRollNumberAdditional").value = "<?php echo str_replace("\n", '\n', $matRollNumberAdditional );  ?>";
				      document.getElementById("matRollNumberAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'matRollNumberAdditional_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>MAT Percentile: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='matPercentileAdditional' id='matPercentileAdditional'   required="true"        validate="validateFloat"    caption="Percentile"   minlength="1"   maxlength="5"      tip="Mention your percentile in the exam. If you don't know your percentile, enter NA."   value=''   allowNA="true"  />
				<?php if(isset($matPercentileAdditional) && $matPercentileAdditional!=""){ ?>
				  <script>
				      document.getElementById("matPercentileAdditional").value = "<?php echo str_replace("\n", '\n', $matPercentileAdditional );  ?>";
				      document.getElementById("matPercentileAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'matPercentileAdditional_error'></div></div>
				</div>
				</div>
			</li>

			<li id="mat1" style="display:none">
				<div class='additionalInfoLeftCol'>
				<label>MAT Date of Examination: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxSmall" type='text' name='matDateOfExaminationAdditional' id='matDateOfExaminationAdditional' readonly maxlength='10'  validate="validateDateForms"  caption="date"            tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('matDateOfExaminationAdditional'),'matDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />
				&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='matDateOfExaminationAdditional_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('matDateOfExaminationAdditional'),'matDateOfExaminationAdditional_dateImg','dd/MM/yyyy'); return false;" />
				<?php if(isset($matDateOfExaminationAdditional) && $matDateOfExaminationAdditional!=""){ ?>
				  <script>
				      document.getElementById("matDateOfExaminationAdditional").value = "<?php echo str_replace("\n", '\n', $matDateOfExaminationAdditional );  ?>";
				      document.getElementById("matDateOfExaminationAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'matDateOfExaminationAdditional_error'></div></div>
				</div>
				</div>
			</li>

			<li id="cmat" style="display:none">
				<div class='additionalInfoLeftCol'>
				<label>CMAT Regn No.: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='cmatRollNumberAdditional' id='cmatRollNumberAdditional'          validate="validateStr"    caption="Roll Number"   minlength="2"   maxlength="50"     tip="Mention your roll number for the exam. If you have not appeared for this examination enter NA."   value=''   allowNA="true"  />
				<?php if(isset($cmatRollNumberAdditional) && $cmatRollNumberAdditional!=""){ ?>
				  <script>
				      document.getElementById("cmatRollNumberAdditional").value = "<?php echo str_replace("\n", '\n', $cmatRollNumberAdditional );  ?>";
				      document.getElementById("cmatRollNumberAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'cmatRollNumberAdditional_error'></div></div>
				</div>
				</div>
				
				<div class='additionalInfoRightCol'>
				<label>CMAT Score: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='cmatScoreAdditional' id='cmatScoreAdditional'     validate="validateInteger"    caption="score"   minlength="1"   maxlength="5"       tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter <b>NA</b>."   value=''   allowNA="true"  />
				<?php if(isset($cmatScoreAdditional) && $cmatScoreAdditional!=""){ ?>
				  <script>
				      document.getElementById("cmatScoreAdditional").value = "<?php echo str_replace("\n", '\n', $cmatScoreAdditional );  ?>";
				      document.getElementById("cmatScoreAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'cmatScoreAdditional_error'></div></div>
				</div>
				</div>
			</li>
			<li id="cmat1" style="display:none">
				<div class='additionalInfoLeftCol'>
				<label>CMAT Percentile: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='cmatRankAdditional' id='cmatRankAdditional'      validate="validateFloat"  caption="Rank"   minlength="2"   maxlength="50"  tip="Mention your Rank in the exam. If you don't know your rank, enter <b>NA</b>."   value=''   allowNA="true"  />
				<?php if(isset($cmatRankAdditional) && $cmatRankAdditional!=""){ ?>
				  <script>
				      document.getElementById("cmatRankAdditional").value = "<?php echo str_replace("\n", '\n', $cmatRankAdditional );  ?>";
				      document.getElementById("cmatRankAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'cmatRankAdditional_error'></div></div>
				</div>
				</div>
			
			</li>

                        
			<li id="xat" style="display:none;">
			  <div class='additionalInfoLeftCol'>
                                <label>XAT Test Center Code: </label>
                                <div class='fieldBoxLarge'>
                                <input class="textboxLarge" type='text' name='Balaji_XATCenterCode' id='Balaji_XATCenterCode'  validate="validateStr"    caption="center code"   minlength="1"   maxlength="100"     tip="Enter your XAT test center code."   title="XAT Test Center Code"  value=''   />
                                <?php if(isset($Balaji_XATCenterCode) && $Balaji_XATCenterCode!=""){ ?>
                                  <script>
                                      document.getElementById("Balaji_XATCenterCode").value = "<?php echo str_replace("\n", '\n', $Balaji_XATCenterCode );  ?>";
                                      document.getElementById("Balaji_XATCenterCode").style.color = "";
                                  </script>
                                <?php } ?>

                                <div style='display:none'><div class='errorMsg' id= 'Balaji_XATCenterCode_error'></div></div>
                                </div>
                                </div>
			  
			  
				<div class='additionalInfoRightCol'>
				<label>XAT REGN NO: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='xatRollNumberAdditional' id='xatRollNumberAdditional'   tip="Mention your Registration number for the exam."    validate="validateStr"    caption="regn no"   minlength="2"   maxlength="50"     value=''   />
				<?php if(isset($xatRollNumberAdditional) && $xatRollNumberAdditional!=""){ ?>
				  <script>
				      document.getElementById("xatRollNumberAdditional").value = "<?php echo str_replace("\n", '\n', $xatRollNumberAdditional );  ?>";
				      document.getElementById("xatRollNumberAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'xatRollNumberAdditional_error'></div></div>
				</div>
				</div>

			 
</li>

			<li id="xat1" style="display:none; border-bottom:1px dotted #c0c0c0; padding-bottom:15px">
				
				<div class="additionalInfoLeftCol">
				<label>XAT Percentile: </label>
				<div class='fieldBoxLarge'>
				   <input class="textboxLarge" class="textboxSmaller"  type='text' name='xatPercentileAdditional' id='xatPercentileAdditional'  validate="validateFloat" allowNA="true"  caption="Percentile"   minlength="1"   maxlength="5"     tip="Mention your percentile in the exam. If you don't know your percentile, enter <b>NA</b>."   value=''  />
					<?php if(isset($xatPercentileAdditional) && $xatPercentileAdditional!=""){ ?>
							<script>
							document.getElementById("xatPercentileAdditional").value = "<?php echo str_replace("\n", '\n', $xatPercentileAdditional );  ?>";
							document.getElementById("xatPercentileAdditional").style.color = "";
							</script>
						  <?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'xatPercentileAdditional_error'></div></div>
				</div>
			      </div>
			</li>
			
			<?php if($action != 'updateScore'):?>
			<li style="display:none">
				<div class='additionalInfoLeftCol'>
				<label>Date Of Birth: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='Balaji_dateOfBirth' id='Balaji_dateOfBirth'         tip="Specify the Date Of Birth."   title="Date Of Birth"  value=''   />
				<?php if(isset($Balaji_dateOfBirth) && $Balaji_dateOfBirth!=""){ ?>
				  <script>
				      document.getElementById("Balaji_dateOfBirth").value = "<?php echo str_replace("\n", '\n', $Balaji_dateOfBirth );  ?>";
				      document.getElementById("Balaji_dateOfBirth").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_dateOfBirth_error'></div></div>
				</div>
				</div>
			</li>
		    
		     <li style="width:100%">
				<div class='additionalInfoLeftCol' style="width:950px">
				<label>Brother's Details: </label>
				<div class='fieldBoxLarge' style="width:630px">
				<textarea name='Balaji_brotherDetail' id='Balaji_brotherDetail'   style="width:618px; height:74px; padding:5px"    tip="Enter the details of your brother including name, education, and work."  validate="validateStr"  minlength="2" maxlength="2000" ></textarea>
				<?php if(isset($Balaji_brotherDetail) && $Balaji_brotherDetail!=""){ ?>
				  <script>
				      document.getElementById("Balaji_brotherDetail").value = "<?php echo str_replace("\n", '\n', $Balaji_brotherDetail );  ?>";
				      document.getElementById("Balaji_brotherDetail").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id='Balaji_brotherDetail_error'></div></div>
				<div class="spacer15 clearFix"></div>
				</div>
				
				</div>
			</li>
			<li style="width:100%">
				<div class='additionalInfoLeftCol' style="width:950px">
				<label>Sister's Details: </label>
				<div class='fieldBoxLarge' style="width:630px">
				<textarea name='Balaji_sisDetail' id='Balaji_sisDetail'   style="width:618px; height:74px; padding:5px"    tip="Enter the details of your sister including name, education, and work."  validate="validateStr"  minlength="2" maxlength="2000" ></textarea>
				<?php if(isset($Balaji_sisDetail) && $Balaji_sisDetail!=""){ ?>
				  <script>
				      document.getElementById("Balaji_sisDetail").value = "<?php echo str_replace("\n", '\n', $Balaji_sisDetail );  ?>";
				      document.getElementById("Balaji_sisDetail").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id='Balaji_sisDetail_error'></div></div>
				<div class="spacer15 clearFix"></div>
				</div>
				
				</div>
			</li>
		    
			
			<li class="secondPart" id="educationField">
				<h3 class="upperCase"> Education </h3>
			</li>
			<li class="secondPart">	
				
				<div class='additionalInfoLeftCol'>
				<label>Std. X Medium of Study : </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='Balaji_MediumX' id='Balaji_MediumX'  tip="Enter std. X medium of study."   title="X Medium of Study" validate="validateStr" minlength="2"   maxlength="100" required="true" caption="std. X medium of study" value=''   />
				<?php if(isset($Balaji_MediumX) && $Balaji_MediumX!=""){ ?>
				  <script>
				      document.getElementById("Balaji_MediumX").value = "<?php echo str_replace("\n", '\n', $Balaji_MediumX );  ?>";
				      document.getElementById("Balaji_MediumX").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_MediumX_error'></div></div>
				</div>
				</div>
			</li>
			<li class="secondPart">
				<div class='additionalInfoLeftCol' >
				<label>Std. XII Medium of Study : </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='Balaji_MediumXII' id='Balaji_MediumXII'  tip="Enter std. XII medium of study."   title="XII Medium of Study" validate="validateStr" minlength="2"  maxlength="100" required="true" caption="std. XII medium of study" value=''   />
				<?php if(isset($Balaji_MediumXII) && $Balaji_MediumXII!=""){ ?>
				  <script>
				      document.getElementById("Balaji_MediumXII").value = "<?php echo str_replace("\n", '\n', $Balaji_MediumXII );  ?>";
				      document.getElementById("Balaji_MediumXII").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_MediumXII_error'></div></div>
				</div>
				</div>
			</li>
			
			<li class="secondPart">
				<h3 class="upperCase"> Graduation Details </h3>
			</li>
			<li class="secondPart">
				<div class='additionalInfoLeftCol' >
				<label>Graduation University : </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='Balaji_gradUniv' id='Balaji_gradUniv'  tip="Enter your graduation university."   title="Graduation University" validate="validateStr" minlength="2"   maxlength="100" required="true" caption="graduation university" value=''   />
				<?php if(isset($Balaji_gradUniv) && $Balaji_gradUniv!=""){ ?>
				  <script>
				      document.getElementById("Balaji_gradUniv").value = "<?php echo str_replace("\n", '\n', $Balaji_gradUniv );  ?>";
				      document.getElementById("Balaji_gradUniv").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_gradUniv_error'></div></div>
				</div>
				</div>
			</li>
			<li class="secondPart">
				<div class='additionalInfoLeftCol' >
				<label>Graduation Degree: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='Balaji_gradDegree' id='Balaji_gradDegree'  tip="Enter your graduation degree."   title="Graduation Degree" required="true" minlength="2" maxlength="100" validate="validateStr" caption="graduation degree" value=''   />
				<?php if(isset($Balaji_gradDegree) && $Balaji_gradDegree!=""){ ?>
				  <script>
				      document.getElementById("Balaji_gradDegree").value = "<?php echo str_replace("\n", '\n', $Balaji_gradDegree );  ?>";
				      document.getElementById("Balaji_gradDegree").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_gradDegree_error'></div></div>
				</div>
				</div>
			</li>
			
			<li class="secondPart">
			    <div class='additionalInfoLeftCol' style="width:88%;">
				    <label>Graduation Degree Duration: </label>
				    <div class='fieldBoxLarge' style="width:450px;" >
				    <input type='radio' onclick="showSpecializationField(this);" validate="validateCheckedGroup"   required="true"   caption="graduation degree duration"  name='Balaji_gradDegreeDuration' id='Balaji_gradDegreeDuration0'   value='3 years degree' title="Graduation Degree Duration"   onmouseover="showTipOnline('Please select your graduation degree duration.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select your graduation degree duration.',this);" onmouseout="hidetip();" > 3 years degree </span>&nbsp;&nbsp;
				    <input type='radio' onclick="showSpecializationField(this);" validate="validateCheckedGroup"   required="true"   caption="the graduation degree duration"  name='Balaji_gradDegreeDuration' id='Balaji_gradDegreeDuration1'   value='4 years degree' title="Graduation Degree Duration"   onmouseover="showTipOnline('Please select your graduation degree duration.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select your graduation degree duration.',this);" onmouseout="hidetip();" > 4 years degree </span>&nbsp;&nbsp;
				    
				    <?php if(isset($Balaji_gradDegreeDuration) && $Balaji_gradDegreeDuration!=""){ ?>
				      <script>
					  radioObj = document.forms["OnlineForm"].elements["Balaji_gradDegreeDuration"];
					  var radioLength = radioObj.length;
					  for(var i = 0; i < radioLength; i++) {
						  radioObj[i].checked = false;
						  if(radioObj[i].value == "<?php echo $Balaji_gradDegreeDuration;?>") {
							  radioObj[i].checked = true;
						  }
					  }
				      </script>
				    <?php } ?>
				    
				    <div style='display:none'><div class='errorMsg' id= 'Balaji_gradDegreeDuration_error'></div></div>
				</div>
				</div>
			</li>
			
			<li id="grad3YearSpec" style="display:none;">
				<div class='additionalInfoLeftCol'  >
				<label>Specialization in 3 years degree :  </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='Balaji_grad3Specialization' id='Balaji_grad3Specialization'  tip="Enter the Specialization in 3 years degree."  minlength="2" maxlength="100" title="Specialization in 3 years degree"  caption="specialisation in 3 years degree" validate="validateStr" value=''  allowNA='true' />
				<?php if(isset($Balaji_grad3Specialization) && $Balaji_grad3Specialization!=""){ ?>
				  <script>
				      document.getElementById("Balaji_grad3Specialization").value = "<?php echo str_replace("\n", '\n', $Balaji_grad3Specialization );  ?>";
				      document.getElementById("Balaji_grad3Specialization").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_grad3Specialization_error'></div></div>
				</div>
				</div>
			</li>
			<li id="grad4YearSpec" style="display:none;">      
				<div class='additionalInfoLeftCol' >
				<label>Specialization in 4 years degree :  </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='Balaji_grad4Specialization' id='Balaji_grad4Specialization'  tip="Enter the Specialization in 4 years degree."  minlength="2" maxlength="100" title="Specialization in 4 years degree"  caption="specialisation in 4 years degree" validate="validateStr" value=''  allowNA='true' />
				<?php if(isset($Balaji_grad4Specialization) && $Balaji_grad4Specialization!=""){ ?>
				  <script>
				      document.getElementById("Balaji_grad4Specialization").value = "<?php echo str_replace("\n", '\n', $Balaji_grad4Specialization );  ?>";
				      document.getElementById("Balaji_grad4Specialization").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_grad4Specialization_error'></div></div>
				</div>
				</div> 
			
			</li>
		     	
			
			
			<li class="secondPart">
				<div class='additionalInfoLeftCol' >
				<label>Medium of Study up to Graduation : </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='Balaji_gradMedium' id='Balaji_gradMedium'  tip="Enter Medium of Study up to Graduation."   title="Enter Medium of Study up to Graduation" minlength="2" maxlength="100" validate="validateStr" required="true" caption="medium of study upto graduation" value=''   />
				<?php if(isset($Balaji_gradMedium) && $Balaji_gradMedium!=""){ ?>
				  <script>
				      document.getElementById("Balaji_gradMedium").value = "<?php echo str_replace("\n", '\n', $Balaji_gradMedium );  ?>";
				      document.getElementById("Balaji_gradMedium").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_gradMedium_error'></div></div>
				</div>
				</div>
			</li>
			
		      <li class="secondPart">
			<div class='additionalInfoLeftCol' style="width:99%;">
				<label style="width:465px;">Have you passed your Bachelor's degree from a recognized University? :</label>
				<div class='fieldBoxLarge'>
				<input type='radio' onchange="setFinalYearField(this)" validate="validateCheckedGroup"   required="true"   caption="yes or no"  name='Balaji_gradRecogUniversity' id='Balaji_gradRecogUniversity0'   value='No' title="Have you passed your Bachelor's degree from a recognized University?"   onmouseover="showTipOnline('Please select one option.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select one option.',this);" onmouseout="hidetip();" >No</span>&nbsp;&nbsp;
				<input type='radio' onchange="setFinalYearField(this)" validate="validateCheckedGroup"   required="true"   caption="yes or no"  name='Balaji_gradRecogUniversity' id='Balaji_gradRecogUniversity1'   value='Yes' title="Have you passed your Bachelor's degree from a recognized University?"   onmouseover="showTipOnline('Please select one option.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select one option.',this);" onmouseout="hidetip();" >Yes</span>&nbsp;&nbsp;
				
				<?php if(isset($Balaji_gradRecogUniversity) && $Balaji_gradRecogUniversity!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["Balaji_gradRecogUniversity"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $Balaji_gradRecogUniversity;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_gradRecogUniversity_error'></div></div>
				</div>
				</div>
			</li>
			
			<li class="secondPart">
			<div class='additionalInfoLeftCol' style="width:99%;">
				<label style="width:465px;">Are you studying in the final year of Bachelor's Degree now? :</label>
				<div class='fieldBoxLarge'>
				<input type='radio'  validate="validateCheckedGroup"   required="true"   caption="yes or no"  name='Balaji_studyFinalYear' id='Balaji_studyFinalYear0'   value='No' title="Are you studying in the final year of Bachelor's Degree now?"   onmouseover="showTipOnline('Please select one option.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select one option.',this);" onmouseout="hidetip();" >No</span>&nbsp;&nbsp;
				<input type='radio'  validate="validateCheckedGroup"   required="true"   caption="yes or no"  name='Balaji_studyFinalYear' id='Balaji_studyFinalYear1'   value='Yes' title="Are you studying in the final year of Bachelor's Degree now?"   onmouseover="showTipOnline('Please select one option.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select one option.',this);" onmouseout="hidetip();" >Yes</span>&nbsp;&nbsp;
				
				<?php if(isset($Balaji_studyFinalYear) && $Balaji_studyFinalYear!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["Balaji_studyFinalYear"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $Balaji_studyFinalYear;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_studyFinalYear_error'></div></div>
				</div>
				</div>
			</li>
			
			<li class="secondPart">
				<h3 class="upperCase"> Graduation Marks </h3>
			</li>
			<li class="secondPart"><label>Graduation 1st Year</label></li>
			<li class="secondPart">	
				<div class='additionalInfoLeftCol' >
				<label>Year of Passing: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='Balaji_grad1YearPassing' id='Balaji_grad1YearPassing'  tip="Enter the passing year of graduation 1st year." minlength="4" maxlength="4"  title="Year of Passing" required="true" caption="year of passing" validate="validateInteger" value='' allowNA='true'  />
				<?php if(isset($Balaji_grad1YearPassing) && $Balaji_grad1YearPassing!=""){ ?>
				  <script>
				      document.getElementById("Balaji_grad1YearPassing").value = "<?php echo str_replace("\n", '\n', $Balaji_grad1YearPassing );  ?>";
				      document.getElementById("Balaji_grad1YearPassing").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_grad1YearPassing_error'></div></div>
				</div>
				</div> 
			      
				<div class='additionalInfoRightCol' >
				<label>Percentage Marks: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='Balaji_grad1YearMarks' id='Balaji_grad1YearMarks'  tip="Enter the percentage/marks of graduation 1st year." minlength="1" maxlength="4"  title="Percentage Marks" required="true" caption="percentage marks" validate="validateInteger" value=''  allowNA='true' />
				<?php if(isset($Balaji_grad1YearMarks) && $Balaji_grad1YearMarks!=""){ ?>
				  <script>
				      document.getElementById("Balaji_grad1YearMarks").value = "<?php echo str_replace("\n", '\n', $Balaji_grad1YearMarks );  ?>";
				      document.getElementById("Balaji_grad1YearMarks").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_grad1YearMarks_error'></div></div>
				</div>
				</div>
			
			</li>
			
			<li class="secondPart"><label>Graduation 2nd Year</label></li>			
			<li class="secondPart">
				<div class='additionalInfoLeftCol' >
				<label>Year of Passing: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='Balaji_grad2YearPassing' id='Balaji_grad2YearPassing'  tip="Enter the passing year of graduation 2nd year." minlength="4" maxlength="4"  title="Year of Passing" required="true" caption="year of passing" validate="validateInteger" value=''  allowNA='true' />
				<?php if(isset($Balaji_grad2YearPassing) && $Balaji_grad2YearPassing!=""){ ?>
				  <script>
				      document.getElementById("Balaji_grad2YearPassing").value = "<?php echo str_replace("\n", '\n', $Balaji_grad2YearPassing );  ?>";
				      document.getElementById("Balaji_grad2YearPassing").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_grad2YearPassing_error'></div></div>
				</div>
				</div> 
			      
				<div class='additionalInfoRightCol' >
				<label>Percentage Marks: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='Balaji_grad2YearMarks' id='Balaji_grad2YearMarks'  tip="Enter the percentage/marks of graduation 2nd year." minlength="1" maxlength="4"  title="Percentage Marks" required="true" caption="percentage marks" validate="validateInteger" value=''  allowNA='true' />
				<?php if(isset($Balaji_grad2YearMarks) && $Balaji_grad2YearMarks!=""){ ?>
				  <script>
				      document.getElementById("Balaji_grad2YearMarks").value = "<?php echo str_replace("\n", '\n', $Balaji_grad2YearMarks );  ?>";
				      document.getElementById("Balaji_grad2YearMarks").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_grad2YearMarks_error'></div></div>
				</div>
				</div>
			
			</li>
			<li class="secondPart"><label>Graduation 3rd Year</label></li>
			<li class="secondPart">
				<div class='additionalInfoLeftCol' >
				<label>Year of Passing: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='Balaji_grad3YearPassing' id='Balaji_grad3YearPassing'  tip="Enter the passing year of graduation 3rd year."  minlength="4" maxlength="4" title="Year of Passing" required="true" caption="year of passing" validate="validateInteger" value=''  allowNA='true' />
				<?php if(isset($Balaji_grad3YearPassing) && $Balaji_grad3YearPassing!=""){ ?>
				  <script>
				      document.getElementById("Balaji_grad3YearPassing").value = "<?php echo str_replace("\n", '\n', $Balaji_grad3YearPassing );  ?>";
				      document.getElementById("Balaji_grad3YearPassing").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_grad3YearPassing_error'></div></div>
				</div>
				</div> 
			      
				<div class='additionalInfoRightCol' >
				<label>Percentage Marks: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='Balaji_grad3YearMarks' id='Balaji_grad3YearMarks'  tip="Enter the percentage/marks of graduation 3rd year."  minlength="1" maxlength="4" title="Percentage Marks" required="true" caption="percentage marks" validate="validateInteger" value='' allowNA='true'  />
				<?php if(isset($Balaji_grad3YearMarks) && $Balaji_grad3YearMarks!=""){ ?>
				  <script>
				      document.getElementById("Balaji_grad3YearMarks").value = "<?php echo str_replace("\n", '\n', $Balaji_grad3YearMarks );  ?>";
				      document.getElementById("Balaji_grad3YearMarks").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_grad3YearMarks_error'></div></div>
				</div>
				</div>
			
			</li>
			<li id="4thYearLabel" style="display:none;"><label>Graduation 4th Year</label></li>
			<li id="grad4thYearField" style="display:none;" >
				<div class='additionalInfoLeftCol' >
				<label>Year of Passing: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='Balaji_grad4YearPassing' id='Balaji_grad4YearPassing'  tip="Enter the passing year of graduation 4th year."  minlength="4" maxlength="4" title="Year of Passing"  caption="year of passing" validate="validateInteger" value=''  allowNA='true' />
				<?php if(isset($Balaji_grad4YearPassing) && $Balaji_grad4YearPassing!=""){ ?>
				  <script>
				      document.getElementById("Balaji_grad4YearPassing").value = "<?php echo str_replace("\n", '\n', $Balaji_grad4YearPassing );  ?>";
				      document.getElementById("Balaji_grad4YearPassing").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_grad4YearPassing_error'></div></div>
				</div>
				</div> 
			      
				<div class='additionalInfoRightCol' >
				<label>Percentage Marks: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='Balaji_grad4YearMarks' id='Balaji_grad4YearMarks'  tip="Enter the percentage/marks of graduation 4th year."  minlength="1" maxlength="4" title="Percentage Marks"  caption="percentage marks" validate="validateInteger" value='' allowNA='true'  />
				<?php if(isset($Balaji_grad4YearMarks) && $Balaji_grad4YearMarks!=""){ ?>
				  <script>
				      document.getElementById("Balaji_grad4YearMarks").value = "<?php echo str_replace("\n", '\n', $Balaji_grad4YearMarks );  ?>";
				      document.getElementById("Balaji_grad4YearMarks").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_grad4YearMarks_error'></div></div>
				</div>
				</div>
			
			</li>
			
			
			<li class="secondPart">
				<h3 class="upperCase">  Post Graduation Details  </h3>
			</li>
			<li class="secondPart">
				
				<div class='additionalInfoLeftCol' >
				<label>Institution: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='Balaji_postGradInst' id='Balaji_postGradInst'  tip="Enter your post graduation institution."   title="Institution"  value=''   />
				<?php if(isset($Balaji_postGradInst) && $Balaji_postGradInst!=""){ ?>
				  <script>
				      document.getElementById("Balaji_postGradInst").value = "<?php echo str_replace("\n", '\n', $Balaji_postGradInst );  ?>";
				      document.getElementById("Balaji_postGradInst").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_postGradInst_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol' >
				<label>University: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='Balaji_postGradUniv' id='Balaji_postGradUniv'  tip="Enter your post graduation university."   title="University"  value=''   />
				<?php if(isset($Balaji_postGradUniv) && $Balaji_postGradUniv!=""){ ?>
				  <script>
				      document.getElementById("Balaji_postGradUniv").value = "<?php echo str_replace("\n", '\n', $Balaji_postGradUniv);  ?>";
				      document.getElementById("Balaji_postGradUniv").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_postGradUniv_error'></div></div>
				</div>
				</div>
			</li>
			
			<li class="secondPart">
				
				<div class='additionalInfoLeftCol' >
				<label>Year of Completion : </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='Balaji_postGradYear' id='Balaji_postGradYear'  tip="Enter your post graduation passing year."   title="Year of Completion" validate="validateInteger" minlength="1"   maxlength="4"  value='' allowNA='true'  />
				<?php if(isset($Balaji_postGradYear) && $Balaji_postGradYear!=""){ ?>
				  <script>
				      document.getElementById("Balaji_postGradYear").value = "<?php echo str_replace("\n", '\n', $Balaji_postGradYear);  ?>";
				      document.getElementById("Balaji_postGradYear").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_postGradYear_error'></div></div>
				</div>
				</div>
			
				
				<div class='additionalInfoRightCol' >
				<label>Degree : </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='Balaji_postGradDegree' id='Balaji_postGradDegree'  tip="Enter degree of your post graduation."   title="Degree"  value=''   />
				<?php if(isset($Balaji_postGradDegree) && $Balaji_postGradDegree!=""){ ?>
				  <script>
				      document.getElementById("Balaji_postGradDegree").value = "<?php echo str_replace("\n", '\n', $Balaji_postGradDegree);  ?>";
				      document.getElementById("Balaji_postGradDegree").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_postGradDegree_error'></div></div>
				</div>
				</div>
		      </li>
			
		      <li class="secondPart">		
				<div class='additionalInfoLeftCol' >
				<label>Final Grade / Marks in %  : </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='Balaji_postGradMarks' id='Balaji_postGradMarks'  tip="Enter Final Grade / Marks in % of your post graduation."   title="Final Grade / Marks in % "  value=''   />
				<?php if(isset($Balaji_postGradMarks) && $Balaji_postGradMarks!=""){ ?>
				  <script>
				      document.getElementById("Balaji_postGradMarks").value = "<?php echo str_replace("\n", '\n', $Balaji_postGradMarks);  ?>";
				      document.getElementById("Balaji_postGradMarks").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_postGradMarks_error'></div></div>
				</div>
				</div>
			</li>
		      
		      
		      <?php
	$graduationCourseName = 'Graduation';
        $graduationYear = '';
	$otherCourses = array();
	$otherCourseYears = array();
	
	if(is_array($educationDetails)) {
		foreach($educationDetails as $educationDetail) {
			if($educationDetail['value']) {
				if($educationDetail['fieldName'] == 'graduationExaminationName') {
					$graduationCourseName = $educationDetail['value'];
				}
				else if($educationDetail['fieldName'] == 'graduationYear') {
					$graduationYear = $educationDetail['value'];
				}
				else {
					for($i=1;$i<=4;$i++) {
						if($educationDetail['fieldName'] == 'graduationExaminationName_mul_'.$i) {
							$otherCourses[$i] = $educationDetail['value'];
						}
						else if($educationDetail['fieldName'] == 'graduationYear_mul_'.$i) {
							$otherCourseYears[$i] = $educationDetail['value'];
						}
					}
				}
			}
		}
	}
	if(isset($graduationEndDate) && $graduationYear!="") {
		$graduationYear = $graduationEndDate;
	}?>
		      
		      <?php
			    $i=0;
			    if(count($otherCourses)>0) { ?>
				<li class="secondPart">
				  <h3 class="upperCase">Other Examinations</h3>
				  <p>Fill in details of other examinations, certificates that you have cleared or won.</p>
				</li>
			      <?php 
				    foreach($otherCourses as $otherCourseId => $otherCourseName) {
					    $pgDegree = 'otherCoursePGDegree_mul_'.$otherCourseId;
					    $pgDegreeVal = $$pgDegree;
					    $i++;
    
			    ?>

			    <li class="secondPart">
				
				<div class='additionalInfoLeftCol'>
				<label> <?php echo $otherCourseName;?> Degree/Certificate </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='<?php echo $pgDegree;?>' id='<?php echo $pgDegree;?>' validate="validateStr"   required="true"   caption="degree of<?php echo $otherCourseName;?>"   minlength="1"   maxlength="50"          tip="Enter the degree of <?=html_escape($otherCourseName);?>"   value=''   allowNA='yes'/>
				<?php if(isset($pgDegreeVal) && $pgDegreeVal!=""){ ?>
				  <script>
				      document.getElementById("<?php echo $pgDegree;?>").value = "<?php echo str_replace("\n", '\n', $pgDegreeVal );  ?>";
				      document.getElementById("<?php echo $pgDegree;?>").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= '<?php echo $pgDegree;?>_error'></div></div>
				</div>
			  
			  </li>	
				<?php
				}
			
			  }
			?>
		      
		      
		      <?php
			$workCompanies = array();
			if(is_array($educationDetails)) {
				foreach($educationDetails as $educationDetail) {
					if($educationDetail['value']) {
						if($educationDetail['fieldName'] == 'weCompanyName') {
							$workCompanies['_mul_0'] = $educationDetail['value'];
						}
						else {
							for($i=1;$i<=2;$i++) {
								if($educationDetail['fieldName'] == 'weCompanyName_mul_'.$i) {
									$workCompanies['_mul_'.$i] = $educationDetail['value'];
								}
							}
						}
						if($educationDetail['fieldName'] == 'weFrom') {
							$workCompaniesExpFrom['_mul_0'] = $educationDetail['value'];
						}
						else {
							for($i=1;$i<=2;$i++) {
								if($educationDetail['fieldName'] == 'weFrom_mul_'.$i) {
									$workCompaniesExpFrom['_mul_'.$i] = $educationDetail['value'];
								}
							}
						}
				
						if($educationDetail['fieldName'] == 'weTill') {
							$workCompaniesExpTill['_mul_0'] = $educationDetail['value'];
						}
						else {
							for($i=1;$i<=2;$i++) {
								if($educationDetail['fieldName'] == 'weTill_mul_'.$i) {
									$workCompaniesExpTill['_mul_'.$i] = $educationDetail['value'];
								}
							}
						}
					}
				}
			} //print_r($workCompaniesExpTill);
			
		
			if(count($workCompanies) > 0) {
		           ?> <li class="secondPart">
                                <h3 class="upperCase">Additional work experience block</h3>
				<p>A must for Executive Batch. Others can also fill up this part.Only those with full-time job experience undertaken in progress after graduation and through a valid appointment order of statutory company with proper terms and conditions including salary and job profile such as sales,marketing,human resources,operations,finance and information technology and soon will be the qualifying work experience.All relevant documents should be produced during the interviews and the selection committee will decide whether to accept the same or not</p>
                            </li>
				<?php
				$j = 0;
				foreach($workCompanies as $workCompanyKey => $workCompany) {
					$workExpInMonthName = 'workExpInMonth'.$workCompanyKey;
					$workExpInMonthValue = $$workExpInMonthName;
					$workExpNatureOfWorkName = 'workExpNatureOfWork'.$workCompanyKey;
					$workExpNatureOfWorkValue = $$workExpNatureOfWorkName;
					$workExpTotalPayName = 'workExpTotalPay'.$workCompanyKey;
					$workExpTotalPayValue = $$workExpTotalPayName;
					$workExpYearMonthName = 'workExpYearMonth'.$workCompanyKey;
					$workExpYearMonthValue = $$workExpYearMonthName;
					$j++;
					
			?>


		      <li class="secondPart">
				<div class='additionalInfoLeftCol'>
				<label>Nature of Work at <?php echo $workCompany; ?>: </label>
				<div class='fieldBoxLarge' >
				<input class="textboxLarge" type='text' name='<?php echo $workExpNatureOfWorkName;?>' id='<?php echo $workExpNatureOfWorkName;?>'  validate="validateStr" minlength="2" maxlength="1000" caption="nature of work at <?php echo $workCompany; ?>"    tip="Enter the nature of work at  <?php echo $workCompany; ?>"   value=''   required="true" allowNA='true'/>
				<?php if(isset($workExpNatureOfWorkValue) && $workExpNatureOfWorkValue!=""){ ?>
				  <script>
				      document.getElementById("<?php echo $workExpNatureOfWorkName;?>").value = "<?php echo str_replace("\n", '\n',$workExpNatureOfWorkValue );  ?>";
				      document.getElementById("<?php echo $workExpNatureOfWorkName;?>").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= '<?php echo $workExpNatureOfWorkName;?>_error'></div></div>
				</div>
				</div>
				
				<div class='additionalInfoRightCol'>
				<label>Total Work Experience at <?php echo $workCompany; ?>: </label>
				<div class='fieldBoxLarge' >
				<input class="textboxSmall" type='text' name='<?php echo $workExpInMonthName;?>' id='<?php echo $workExpInMonthName;?>'  validate="validateFloat" minlength="1" maxlength="10" caption="total work experience at <?php echo $workCompany; ?>"    tip="Enter the total work experience you worked at  <?php echo $workCompany; ?>"   value=''   required="true" allowNA='true'/>
				<?php if(isset($workExpInMonthValue) && $workExpInMonthValue!=""){ ?>
				  <script>
				      document.getElementById("<?php echo $workExpInMonthName;?>").value = "<?php echo str_replace("\n", '\n', $workExpInMonthValue );  ?>";
				      document.getElementById("<?php echo $workExpInMonthName;?>").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= '<?php echo $workExpInMonthName;?>_error'></div></div>
				
				<select  style="width:115px; margin-top:5px;" required='true' name='<?php echo $workExpYearMonthName;?>' id='<?php echo $workExpYearMonthName;?>'   validate="validateSelect" caption="one of the option"   onmouseover="showTipOnline('Please select one the option.',this);" onmouseout="hidetip();" ><option value='Months' >Months</option><option value='Years' >Years</option></select>
				<?php if(isset($workExpYearMonthValue) && $workExpYearMonthValue!=""){ ?>
			      <script>
				  var selObj = document.getElementById("<?php echo $workExpYearMonthName;?>"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $workExpYearMonthName;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= '<?php echo $workExpYearMonthName;?>_error'></div></div>
				</div>
				</div>
			</li>
			  <li class="secondPart">
				
				<div class='additionalInfoLeftCol'>
				<label>Total Pay at <?php echo $workCompany; ?>:</label>
				<div class='fieldBoxLarge' >
				<input class="textboxLarge" type='text' name='<?php echo $workExpTotalPayName; ?>' id='<?php echo $workExpTotalPayName; ?>'  validate="validateFloat" minlength="1" maxlength="10" caption="salary"    tip="Enter the monthly salary at <?php echo $workCompany; ?>"   value=''   required="true" allowNA='true'/>&nbsp;
				<?php if(isset($workExpTotalPayValue) && $workExpTotalPayValue!=""){ ?>
				  <script>
				      document.getElementById("<?php echo $workExpTotalPayName; ?>").value = "<?php echo str_replace("\n", '\n', $workExpTotalPayValue );  ?>";
				      document.getElementById("<?php echo $workExpTotalPayName; ?>").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= '<?php echo $workExpTotalPayName; ?>_error'></div></div>
				</div>
				</div>
				
			</li>
			<?php
				}
			}
			?>	
		      
		      <li class="secondPart">
				<h3 class="upperCase">  Extra-Curricular Activities  </h3>
				<p>This is not not a compulsory part.Only those claims which can be sustaintiated with legitimate certificates need to be mentioned here.List not more than three significant achievements in extra-curricular activities.</p>
			</li>
			<li class="secondPart">
				
				<div class='additionalInfoLeftCol' >
				<label>Achievement 1: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='Balaji_1extraCurrAchievement' id='Balaji_1extraCurrAchievement'  tip="Enter your Achievement."  title="Achievements"  value=''   />
				<?php if(isset($Balaji_1extraCurrAchievement) && $Balaji_1extraCurrAchievement!=""){ ?>
				  <script>
				      document.getElementById("Balaji_1extraCurrAchievement").value = "<?php echo str_replace("\n", '\n', $Balaji_1extraCurrAchievement );  ?>";
				      document.getElementById("Balaji_1extraCurrAchievement").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_extraCurrAchievement_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol' >
				<label>Year: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='Balaji_1extraCurryear' id='Balaji_1extraCurryear'  tip="Enter your achievement year."   title="Year" caption="year" minlength="4"   maxlength="4"  validate="validateInteger"  value=''  allowNA='true' />
				<?php if(isset($Balaji_1extraCurryear) && $Balaji_1extraCurryear!=""){ ?>
				  <script>
				      document.getElementById("Balaji_1extraCurryear").value = "<?php echo str_replace("\n", '\n', $Balaji_1extraCurryear);  ?>";
				      document.getElementById("Balaji_1extraCurryear").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_1extraCurryear_error'></div></div>
				</div>
				</div>
			</li>
			<li class="secondPart">
				<div class='additionalInfoLeftCol' >
				<label>Level: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='Balaji_1extraCurrLevel' id='Balaji_1extraCurrLevel'  tip="Enter your achievement level."   title="Level"  value=''   />
				<?php if(isset($Balaji_1extraCurrLevel) && $Balaji_1extraCurrLevel!=""){ ?>
				  <script>
				      document.getElementById("Balaji_1extraCurrLevel").value = "<?php echo str_replace("\n", '\n', $Balaji_1extraCurrLevel);  ?>";
				      document.getElementById("Balaji_1extraCurrLevel").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_1extraCurrLevel_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol' >
				<label>Personal Traits: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='Balaji_1extraCurrtraits' id='Balaji_1extraCurrtraits'  tip="Enter your personal traits."   title="Personal Traits"  value=''   />
				<?php if(isset($Balaji_1extraCurrtraits) && $Balaji_1extraCurrtraits!=""){ ?>
				  <script>
				      document.getElementById("Balaji_1extraCurrtraits").value = "<?php echo str_replace("\n", '\n', $Balaji_1extraCurrtraits);  ?>";
				      document.getElementById("Balaji_1extraCurrtraits").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_1extraCurrtraits_error'></div></div>
				</div>
				</div>
			</li>
			
			<li class="secondPart">
			 	
				<div class='additionalInfoLeftCol' >
				<label>Achievement 2: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='Balaji_2extraCurrAchievement' id='Balaji_2extraCurrAchievement'  tip="Enter your Achievements."   title="Achievements"  value=''   />
				<?php if(isset($Balaji_2extraCurrAchievement) && $Balaji_2extraCurrAchievement!=""){ ?>
				  <script>
				      document.getElementById("Balaji_2extraCurrAchievement").value = "<?php echo str_replace("\n", '\n', $Balaji_2extraCurrAchievement );  ?>";
				      document.getElementById("Balaji_2extraCurrAchievement").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_2extraCurrAchievement_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol' >
				<label>Year: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='Balaji_2extraCurryear' id='Balaji_2extraCurryear'  tip="Enter your achievement year."   title="Year"  caption="year" minlength="4"   maxlength="4"  validate="validateInteger"  value=''  allowNA='true'   />
				<?php if(isset($Balaji_2extraCurryear) && $Balaji_2extraCurryear!=""){ ?>
				  <script>
				      document.getElementById("Balaji_2extraCurryear").value = "<?php echo str_replace("\n", '\n', $Balaji_2extraCurryear);  ?>";
				      document.getElementById("Balaji_2extraCurryear").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_2extraCurryear_error'></div></div>
				</div>
				</div>
			</li>
			<li class="secondPart">
				<div class='additionalInfoLeftCol'>
				<label>Level: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='Balaji_2extraCurrLevel' id='Balaji_2extraCurrLevel'  tip="Enter your achievement level."   title="Level"  value=''   />
				<?php if(isset($Balaji_2extraCurrLevel) && $Balaji_2extraCurrLevel!=""){ ?>
				  <script>
				      document.getElementById("Balaji_2extraCurrLevel").value = "<?php echo str_replace("\n", '\n', $Balaji_2extraCurrLevel);  ?>";
				      document.getElementById("Balaji_2extraCurrLevel").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_2extraCurrLevel_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol' >
				<label>Personal Traits: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='Balaji_2extraCurrtraits' id='Balaji_2extraCurrtraits'  tip="Enter your personal traits."   title="Personal Traits"  value=''   />
				<?php if(isset($Balaji_2extraCurrtraits) && $Balaji_2extraCurrtraits!=""){ ?>
				  <script>
				      document.getElementById("Balaji_2extraCurrtraits").value = "<?php echo str_replace("\n", '\n', $Balaji_2extraCurrtraits);  ?>";
				      document.getElementById("Balaji_2extraCurrtraits").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_2extraCurrtraits_error'></div></div>
				</div>
				</div>
			</li>
			
			<li class="secondPart">
				
				<div class='additionalInfoLeftCol' >
				<label>Achievement 3: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='Balaji_3extraCurrAchievement' id='Balaji_3extraCurrAchievement'  tip="Enter your Achievements."   title="Achievements"  value=''   />
				<?php if(isset($Balaji_3extraCurrAchievement) && $Balaji_3extraCurrAchievement!=""){ ?>
				  <script>
				      document.getElementById("Balaji_3extraCurrAchievement").value = "<?php echo str_replace("\n", '\n', $Balaji_3extraCurrAchievement );  ?>";
				      document.getElementById("Balaji_3extraCurrAchievement").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_3extraCurrAchievement_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol' >
				<label>Year: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='Balaji_3extraCurryear' id='Balaji_3extraCurryear'  tip="Enter your achievement year."   title="Year"  caption="year" minlength="4"   maxlength="4"  validate="validateInteger"  value=''  allowNA='true'  />
				<?php if(isset($Balaji_3extraCurryear) && $Balaji_3extraCurryear!=""){ ?>
				  <script>
				      document.getElementById("Balaji_3extraCurryear").value = "<?php echo str_replace("\n", '\n', $Balaji_3extraCurryear);  ?>";
				      document.getElementById("Balaji_3extraCurryear").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_3extraCurryear_error'></div></div>
				</div>
				</div>
			</li>
			<li class="secondPart">
				<div class='additionalInfoLeftCol' >
				<label>Level: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='Balaji_3extraCurrLevel' id='Balaji_3extraCurrLevel'  tip="Enter your achievement level."   title="Level"  value=''   />
				<?php if(isset($Balaji_3extraCurrLevel) && $Balaji_3extraCurrLevel!=""){ ?>
				  <script>
				      document.getElementById("Balaji_3extraCurrLevel").value = "<?php echo str_replace("\n", '\n', $Balaji_3extraCurrLevel);  ?>";
				      document.getElementById("Balaji_3extraCurrLevel").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_3extraCurrLevel_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol' >
				<label>Personal Traits: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='Balaji_3extraCurrtraits' id='Balaji_3extraCurrtraits'  tip="Enter your personal traits."   title="Personal Traits"  value=''   />
				<?php if(isset($Balaji_3extraCurrtraits) && $Balaji_3extraCurrtraits!=""){ ?>
				  <script>
				      document.getElementById("Balaji_3extraCurrtraits").value = "<?php echo str_replace("\n", '\n', $Balaji_3extraCurrtraits);  ?>";
				      document.getElementById("Balaji_3extraCurrtraits").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_3extraCurrtraits_error'></div></div>
				</div>
				</div>
			</li>
			
			<li class="secondPart">
				<h3 class="upperCase">  Other  </h3>
			</li>
			<li class="secondPart">
				<div class='additionalInfoLeftCol'>
				<label> Outstanding Academic Performer: </label>
				<div class='fieldBoxLarge'>
				<input type='radio'  validate="validateCheckedGroup"   required="true"   caption="yes or no"  name='Balaji_outAcademicPerf' id='Balaji_outAcademicPerf0'   value='No' title="Are you a Outstanding Academic Performer?"   onmouseover="showTipOnline('Are you a outstanding academic performer? If yes then please select Yes.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Are you a outstanding academic performer? If yes then please select Yes.',this);" onmouseout="hidetip();" >No</span>&nbsp;&nbsp;
				<input type='radio'  validate="validateCheckedGroup"   required="true"   caption="yes or no"  name='Balaji_outAcademicPerf' id='Balaji_outAcademicPerf1'   value='Yes'    title="Outstanding Academic Performer?"   onmouseover="showTipOnline('Are you a outstanding academic performer? If yes then please select Yes.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Are you a outstanding academic performer? If yes then please select Yes.',this);" onmouseout="hidetip();" >Yes</span>&nbsp;&nbsp;
				<?php if(isset($Balaji_outAcademicPerf) && $Balaji_outAcademicPerf!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["Balaji_outAcademicPerf"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $Balaji_outAcademicPerf;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_outAcademicPerf_error'></div></div>
				</div>
				</div>
			</li>
			
			<li class="secondPart">
				<div class='additionalInfoLeftCol'>
				<label> Private/external/distance learning mode student: </label>
				<div class='fieldBoxLarge'>
				<input type='radio'  validate="validateCheckedGroup"   required="true"   caption="yes or no"  name='Balaji_learningModeStudent' id='Balaji_learningModeStudent0'   value='No' title="Private/external/distance learning mode student"   onmouseover="showTipOnline('Private/external/distance learning mode student? If yes then please select Yes.',this);" onmouseout="hidetip();" onclick="showLearningField(this);"></input><span  onmouseover="showTipOnline('Private/external/distance learning mode student? If yes then please select Yes.',this);" onmouseout="hidetip();"  >No</span>&nbsp;&nbsp;
				<input type='radio'  validate="validateCheckedGroup"   required="true"   caption="yes or no"  name='Balaji_learningModeStudent' id='Balaji_learningModeStudent1'   value='Yes' title="Private/external/distance learning mode student"   onmouseover="showTipOnline('Private/external/distance learning mode student? If yes then please select Yes.',this);" onmouseout="hidetip();" onclick="showLearningField(this);"></input><span  onmouseover="showTipOnline('Private/external/distance learning mode student? If yes then please select Yes.',this);" onmouseout="hidetip();">Yes</span>&nbsp;&nbsp;
				<?php if(isset($Balaji_learningModeStudent) && $Balaji_learningModeStudent!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["Balaji_learningModeStudent"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $Balaji_learningModeStudent;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_learningModeStudent_error'></div></div>
				</div>
				</div>
				
				<div class='additionalInfoRightCol' id="learningMode" style="display:none;">
				<label>Learning Mode Course: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='Balaji_learningMode' id='Balaji_learningMode'  tip="Enter learning mode course."  validate="validateStr" title="Learning Mode Course"  minlength="2"  maxlength="1000" caption="learning mode course" value=''   />
				<?php if(isset($Balaji_learningMode) && $Balaji_learningMode!=""){ ?>
				  <script>
				      document.getElementById("Balaji_learningMode").value = "<?php echo str_replace("\n", '\n', $Balaji_learningMode);  ?>";
				      document.getElementById("Balaji_learningMode").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_learningMode_error'></div></div>
				</div>
			</li>
			
			<li class="secondPart">
				<div class='additionalInfoLeftCol'>
				<label> Have Gap in Education?: </label>
				<div class='fieldBoxLarge'>
				<input type='radio'  validate="validateCheckedGroup"   required="true"   caption="yes or no"  name='Balaji_gapInEdu' id='Balaji_gapInEdu0'   value='No' title="Have Gap in Education?"   onmouseover="showTipOnline('Do you have Gap in Education? If yes then please select Yes.',this);" onmouseout="hidetip();" onclick="showGapReasonField(this);"></input><span  onmouseover="showTipOnline('Do you have Gap in Education? If yes then please select Yes.',this);" onmouseout="hidetip();" >No</span>&nbsp;&nbsp;
				<input type='radio'  validate="validateCheckedGroup"   required="true"   caption="yes or no"  name='Balaji_gapInEdu' id='Balaji_gapInEdu1'   value='Yes' title="Have Gap in Education?"   onmouseover="showTipOnline('Have Gap in Education? If yes then please select Yes.',this);" onmouseout="hidetip();" onclick="showGapReasonField(this);"></input><span  onmouseover="showTipOnline('Have Gap in Education? If yes then please select Yes.',this);" onmouseout="hidetip();" >Yes</span>&nbsp;&nbsp;
				<?php if(isset($Balaji_gapInEdu) && $Balaji_gapInEdu!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["Balaji_gapInEdu"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $Balaji_gapInEdu;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_gapInEdu_error'></div></div>
				</div>
				</div>
				
				<div class='additionalInfoRightCol' id="gapReason" style="display:none;">
				<label>Reason for Gap in Education:</label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='Balaji_gapReason' id='Balaji_gapReason'  tip="Enter reason for gap in education."  validate="validateStr" title="Reason for Gap in Education" minlength="2"   maxlength="1000" caption="reason for gap in education" value=''   />
				<?php if(isset($Balaji_gapReason) && $Balaji_gapReason!=""){ ?>
				  <script>
				      document.getElementById("Balaji_gapReason").value = "<?php echo str_replace("\n", '\n', $Balaji_gapReason);  ?>";
				      document.getElementById("Balaji_gapReason").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Balaji_gapReason_error'></div></div>
				</div>
			</li>
			
			<li>
			  <a href="javascript:void(0);" id="loadMoreButton" onclick="$j('.secondPart').show();$j('#loadMoreButton').hide();clickLoadMore=true;showGradSpecField();" style="margin-left: 835px;font-size:18px;" >Load More</a>
			</li>
			
			<li>
				<h3 class=upperCase'>Declaration</h3>
				<label style="font-weight:normal; padding-top:0">Declaration:</label>
				<div class='fieldBoxLarge' style="width:620px; color:#666666; font-style:italic">

1. I declare that the information given by me in this application is true to the best of my knowledge.<br/>
2. I hereby undertake to abide by the instructions contained in the institute website  and  all the  disciplinary rules  of the Institute and authorize the Director to initiate any suitable action, in case I infringe the rules and regulations as laid down by the institute.<br/>
3. I have carefully read and understood the rules and instructions regarding the refund of fees mentioned in the institute website and I hereby undertake to abide by the same.<br/>
4. Application form/ Registration Fee is not refundable.<br/>
5. I hereby declare that I am medically fit in all respects and I undertake to maintain more than the expected attendance.<br/>
6. I agree to abide by any government regulations regarding admissions if implemented by Sri Balaji Society.<br/>
7. I undertake that I will regularly attend the classes even after the campus placements.<br/>
8. Any dispute will be settled by arbitration, subject to the jurisdiction of Pune.<br/>

				</div>
				<div class='additionalInfoLeftCol'>
				<label>I agree to the terms stated above: </label>
				<div class='fieldBoxLarge'>
				<input type='checkbox'  validate="validateChecked" checked   required="true"   caption="Please check to accept terms"   name='Balaji_agreeToTerms[]' id='Balaji_agreeToTerms0'   value='1'    title="I agree to the terms stated above"   onmouseover="showTipOnline('Please check to accept terms',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please check to accept terms',this);" onmouseout="hidetip();" ></span>&nbsp;&nbsp;
				<?php if(isset($Balaji_agreeToTerms) && $Balaji_agreeToTerms!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["Balaji_agreeToTerms[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$Balaji_agreeToTerms);
						    for($x=0;$x<count($arr);$x++){ ?>
							  if(objCheckBoxes[i].value == "<?php echo $arr[$x];?>") {
								  objCheckBoxes[i].checked = true;
							  }
					      <?php
						    }
					      ?>
				    }
				</script>
			      <?php } ?>
				<div style='display:none'><div class='errorMsg' id= 'Balaji_agreeToTerms0_error'></div></div>
				</div>
				</div>
			</li>
			
			<li>
                                <div class='additionalInfoLeftCol' style="width:700px;">
                                <label>Upload a scanned copy of your signature: </label>
                                <div style="padding-left:312px; float:left">
                                <img width="195" height="192" src="<?php echo $signatureImageBalaji;?>" alt="Profile Pic" />
                                <input type='file' name='userApplicationfile[]' id='signatureImageBalaji'  size="30" required="true"  onmouseover="showTipOnline('Your signature is required with this form. If you do not have an electronic copy of your signature, sign on a paper and scan it. Then upload the scanned image file here.',this);"  onmouseout = "hidetip();" caption="signature image"/>
                                <input type='hidden' name='signatureImageBalajiValid' value='extn:png,gif,jpg,jpeg'>
                                <br/><span class="imageSizeInfo">(Image dimention :600x400 pixels, Image Size : less than 1 MB)</span>
                                <div style='display:none'><div class='errorMsg' id= 'signatureImageBalaji_error'></div></div>
                                </div>
                                </div>
                        </li>
			<?php endif; ?>
		</ul>
	</div>
</div>

<script>getCitiesForCountryOnline("",false,"",false);</script><script>getCitiesForCountryOnlineCorrespondence("",false,"",false);</script><?php if(isset($city) && $city!=""){ ?>
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

  
  
	for(var j=0; j<4; j++){
		checkTestScore(document.getElementById('Balaji_testNames'+j));
	}
	
	
	
  
	function setFinalYearField(obj){
	  if(obj){
	          if (obj.value=="No") {
		      jQuery("#Balaji_studyFinalYear1").attr('checked', true);
		  }else{
		      jQuery("#Balaji_studyFinalYear0").attr('checked', true);
		  }
	    }		 
	}
	
	function showCertificateField(obj) {
	  if (obj) {
		  if (obj.value=="Yes") {
		    document.getElementById('phyHandiAttach').style.display = 'block';
		    document.getElementById('Balaji_phyHandiAttach0').setAttribute('validate','validateCheckedGroup');
		    document.getElementById('Balaji_phyHandiAttach1').setAttribute('validate','validateCheckedGroup');
		    
		  }else{
		    document.getElementById('phyHandiAttach').style.display = 'none';
		    document.getElementById('Balaji_phyHandiAttach0').checked=false;
		    document.getElementById('Balaji_phyHandiAttach1').checked=false;
		    document.getElementById('Balaji_phyHandiAttach0').removeAttribute('validate');
		    document.getElementById('Balaji_phyHandiAttach1').removeAttribute('validate');
		    document.getElementById('Balaji_phyHandiAttach_error').innerHTML = '';
		    document.getElementById('Balaji_phyHandiAttach_error').style.display = 'none';
		    document.getElementById('Balaji_phyHandiAttach_error').parentNode.style.display = 'none';
		  }
	    
	  }
	  
	}
	
	function showGapReasonField(obj){
	  if (obj) {
		  if (obj.value=="Yes") {
		    document.getElementById('gapReason').style.display = 'block';
		    document.getElementById('Balaji_gapReason').setAttribute('required','true');
		  }else{
		    document.getElementById('gapReason').style.display = 'none';
		    document.getElementById('Balaji_gapReason').value='';
		    document.getElementById('Balaji_gapReason').removeAttribute('required');
		    document.getElementById('Balaji_gapReason_error').innerHTML = '';
		    document.getElementById('Balaji_gapReason_error').parentNode.style.display = 'none';
		  }  
	  }
	}
	
	function showLearningField(obj){ 
	  if (obj) {
		  if (obj.value=="Yes") {
		    document.getElementById('learningMode').style.display = 'block';
		    document.getElementById('Balaji_learningMode').setAttribute('required','true');
		  }else{
		    document.getElementById('learningMode').style.display = 'none';
		    document.getElementById('Balaji_learningMode').value='';
		    document.getElementById('Balaji_learningMode').removeAttribute('required');
		    document.getElementById('Balaji_gapReason_error').innerHTML = '';
		    document.getElementById('Balaji_gapReason_error').parentNode.style.display = 'none';
		  }  
	  }
	}
	
      
	var phyHandicapped = '<?php echo $Balaji_phyHandi;?>';
	if (phyHandicapped == 'Yes')
	{
	    $('phyHandiAttach').style.display = 'block';
	}
	
	var gapReason = '<?php echo $Balaji_gapInEdu;?>';
	if (gapReason == 'Yes')
	{
	    $('gapReason').style.display = 'block';
	}
	
	var learningMode = '<?php echo $Balaji_learningModeStudent;?>';
	if (learningMode == 'Yes')
	{
	    $('learningMode').style.display = 'block';
	}
	
	function showGradSpecField(){
		var gradSpec = '<?php echo $Balaji_gradDegreeDuration;?>'; 
		if (gradSpec == '3 years degree' )
		{
			$('grad3YearSpec').style.display = 'block';
			
		}
		if (gradSpec == '4 years degree' )
		{
		    $('grad4YearSpec').style.display = 'block';
		    $('grad4thYearField').style.display = 'block';
		    $('4thYearLabel').style.display = 'block';
		}
	}
	
  </script>
