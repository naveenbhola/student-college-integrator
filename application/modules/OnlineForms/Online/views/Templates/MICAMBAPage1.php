<script>
function in_array (needle, haystack, argStrict) {
  var key = '',
    strict = !! argStrict;

  if (strict) {
    for (key in haystack) {
      if (haystack[key] === needle) {
        return true;
      }
    }
  } else {
    for (key in haystack) {
      if (haystack[key] == needle) {
        return true;
      }
    }
  }

  return false;
}


  function showOtherMultiSelectOption(selObj,texboxid,liid){
	var selectedArray = new Array();
	var count =0;
 	for (i=0; i<selObj.options.length; i++) {
		if (selObj.options[i].selected) {
		  selectedArray[count] = selObj.options[i].value;
		  count++;
		}
	}
	if(in_array('Others',selectedArray)==true){
		$(liid).style.display='';
		$(texboxid).setAttribute('required','true');
		
		
	}else{
		$(liid).style.display='none';
		$(texboxid).removeAttribute('required');
	}

  }
  
  function checkTestScore(obj){
	var key = obj.value.toLowerCase();
	
	if(obj.value == "XAT" || obj.value == "CAT"){
	    var objects1 = new Array(key+"RollNumberAdditional",key+"ScoreAdditional",key+"PercentileAdditional");
	}
	if(obj.value == "ATMA" || obj.value == "MAT" ){
	    var objects1 = new Array(key+"RollNumberAdditional",key+"ScoreAdditional",key+"PercentileAdditional",key+"DateOfExaminationAdditional");
	}
	
	if(obj.value=="XAT2014"){
	   var objects1 = new Array("Xat2014Id","XATScore2014MICA","XATPercent2014MICA");
	}
	
	if(obj.value == "GMAT"){
	    var objects1 = new Array(key+"ScoreAdditional");
	}
	if(obj.value == "CMAT"){
	    var objects1 = new Array(key+"RollNumberAdditional",key+"RankAdditional",key+"ScoreAdditional",key+"DateOfExaminationAdditional");
	}
	

	if(obj){
	      if( obj.checked == false ){
		if(obj.value == "CAT"){
		   $(key+'1').style.display = 'none';
		   $(key+'2').style.display = 'none';
		}
		if(obj.value == "XAT"){
		 
		    for(i=1;i<=5;i++){
			 $('xat'+i).style.display = 'none';
		      }
		    var objectsArrRem = new Array("quantXATScore2013MICA","quantXATPercent2013MICA","ellrMATScore2013MICA","ellrMATPercent2013MICA","decsskillsMATScore2013MICA","decsMATPercent2013MICA");
		      for(i=0;i<objectsArrRem.length;i++){
			$(objectsArrRem[i]).value = '';
			$(objectsArrRem[i]).removeAttribute('required');
			$(objectsArrRem[i]+'_error').innerHTML = '';
			$(objectsArrRem[i]+'_error').parentNode.style.display = 'none';
		      }
		
		}
		if(obj.value == "XAT2014"){
		   for(i=1;i<=5;i++){
			    $('xat'+i+'1').style.display = 'none';
		         }
		    var objectsArrRem = new Array("Xat2014Id","XATScore2014MICA","XATPercent2014MICA","quantXATScore2014MICA","quantXATPercent2014MICA","ellrMATScore2014MICA","ellrMATPercent2014MICA","decsskillsMATScore2014MICA","decsMATPercent2014MICA");
		      for(i=0;i<objectsArrRem.length;i++){
			$(objectsArrRem[i]).value = '';
			$(objectsArrRem[i]).removeAttribute('required');
			$(objectsArrRem[i]+'_error').innerHTML = '';
			$(objectsArrRem[i]+'_error').parentNode.style.display = 'none';
		      }
		 
		}
		if(obj.value == "GMAT"){
		
		    for(i=1;i<=6;i++){
			    $('gmat'+i).style.display = 'none';
		         }
		    var objectsArrRem = new Array("gmatPercentileAdditional","gmatDateOfExaminationAdditional","gmatRollNumberAdditional","verbalGMATScoreMICA","verbalGMATPercentMICA","quantGMATScoreMICA","quantGMATPercentMICA","reasoningGMATScoreMICA","reasoningGMATPercentMICA","analyticalGMATScoreMICA","analyticalGMATPercentMICA");
		      for(i=0;i<objectsArrRem.length;i++){
			$(objectsArrRem[i]).value = '';
			$(objectsArrRem[i]).removeAttribute('required');
			$(objectsArrRem[i]+'_error').innerHTML = '';
			$(objectsArrRem[i]+'_error').parentNode.style.display = 'none';
		      }
		  
		}
		if(obj.value == "CAT"){
		     for(i=3;i<=4;i++){
			    $('cat'+i).style.display = 'none';
		         }
		    var objectsArrRem = new Array("qadiCATScoreMICA","qadiCATPercentileMICA","valrCATScoreMICA","valrCATPercentileMICA");
		      for(i=0;i<objectsArrRem.length;i++){
			$(objectsArrRem[i]).value = '';
			$(objectsArrRem[i]).removeAttribute('required');
			$(objectsArrRem[i]+'_error').innerHTML = '';
			$(objectsArrRem[i]+'_error').parentNode.style.display = 'none';
		      }
		
		}
		
		if(obj.value == "ATMA"){
		  
		     for(i=1;i<=5;i++){
			    $('atma'+i).style.display = 'none';
		         }
		    var objectsArrRem = new Array("verbalATMAScoreMICA","verbalATMAPercentMICA","quantATMAcoreMICA","analyticalATMAScoreMICA","analyticalATMAPercentMICA","quantATMAPercentMICA");
		      for(i=0;i<objectsArrRem.length;i++){
			$(objectsArrRem[i]).value = '';
			$(objectsArrRem[i]).removeAttribute('required');
			$(objectsArrRem[i]+'_error').innerHTML = '';
			$(objectsArrRem[i]+'_error').parentNode.style.display = 'none';
		      }
		}
		if(obj.value=="CMAT"){
			 for(i=1;i<=5;i++){
			    $('cmat'+i).style.display = 'none';
		         }
		    var objectsArrRem = new Array("quantCMATScoreMICA","logicalCMATReasoningMICA","langCMATScoreMICA","generalCMATScoreMICA");
		      for(i=0;i<objectsArrRem.length;i++){
			$(objectsArrRem[i]).value = '';
			$(objectsArrRem[i]).removeAttribute('required');
			$(objectsArrRem[i]+'_error').innerHTML = '';
			$(objectsArrRem[i]+'_error').parentNode.style.display = 'none';
		      }
	
		}
		
		if(obj.value=="MAT"){
		      for(i=1;i<=8;i++){
			    $('mat'+i).style.display = 'none';
		      }
		
		      var objectsArrRem = new Array("formMATnumberMICA","langMATcomprehensionScoreMICA","langMATcomprehensionPercentageMICA","mathskillsMATScoreMICA","mathskillsMATPercentMICA","DASMATScoreMICA","DASMATPercentMICA","IGEMATScoreMICA","IGEMATPercentMICA","reasoningMATScoreMICA","reasoningMATPercentileMICA");
		      for(i=0;i<objectsArrRem.length;i++){
			$(objectsArrRem[i]).value = '';
			$(objectsArrRem[i]).removeAttribute('required');
			$(objectsArrRem[i]+'_error').innerHTML = '';
			$(objectsArrRem[i]+'_error').parentNode.style.display = 'none';
		      }
			
		}
		//Set the required paramters when any Exam is hidden
		resetExamFields(objects1); // is this a requirement ?
	      }
	      else{
		
		
		    if(obj.value == "CAT"){
			$(key+'1').style.display = '';
			$(key+'2').style.display = '';
		    }
	
		    //Set the required paramters when any Exam is shown
		    if(obj.value!="XAT2014"){
			setExamFields(objects1);
		    }
		    if(obj.value == "CAT"){
			$('cat3').style.display = '';
			$('cat4').style.display = '';
			$('qadiCATScoreMICA').setAttribute('required','true');
			$('qadiCATPercentileMICA').setAttribute('required','true');
			$('valrCATScoreMICA').setAttribute('required','true');
			$('valrCATPercentileMICA').setAttribute('required','true');
		    }
		    
		    if(obj.value=="GMAT"){
		      	for(i=1;i<=6;i++)
			    $('gmat'+i).style.display = '';
			$('gmatRollNumberAdditional').setAttribute('required','true');
			 $('verbalGMATScoreMICA').setAttribute('required','true');
			 $('verbalGMATPercentMICA').setAttribute('required','true');
			 $('analyticalGMATScoreMICA').setAttribute('required','true');
			 $('analyticalGMATPercentMICA').setAttribute('required','true');
			 $('reasoningGMATScoreMICA').setAttribute('required','true');
			 $('reasoningGMATPercentMICA').setAttribute('required','true');
			 $('quantGMATScoreMICA').setAttribute('required','true');
			 $('quantGMATPercentMICA').setAttribute('required','true');
			   $('gmatPercentileAdditional').setAttribute('required','true');
		          $('gmatDateOfExaminationAdditional').setAttribute('required','true');
		    }
		 
		 
		    if(obj.value == "ATMA"){
		
			for(i=1;i<=5;i++)
			    $('atma'+i).style.display = '';
			 $('verbalATMAScoreMICA').setAttribute('required','true');
			 $('verbalATMAPercentMICA').setAttribute('required','true');
			 $('analyticalATMAScoreMICA').setAttribute('required','true');
			 $('analyticalATMAPercentMICA').setAttribute('required','true');
			 $('quantATMAcoreMICA').setAttribute('required','true');
			 $('quantATMAPercentMICA').setAttribute('required','true');
			 
		    }

		     if(obj.value == "CMAT"){
			
		    for(i=1;i<=5;i++)
			    $('cmat'+i).style.display = '';
			 $('quantCMATScoreMICA').setAttribute('required','true');
			 $('logicalCMATReasoningMICA').setAttribute('required','true');
			 $('langCMATScoreMICA').setAttribute('required','true');
			 $('generalCMATScoreMICA').setAttribute('required','true');
			 
		    }

		   if(obj.value=="MAT"){
		
		      for(i=1;i<=8;i++)
			    $('mat'+i).style.display = '';
			$('formMATnumberMICA').setAttribute('required','true');
			 $('langMATcomprehensionScoreMICA').setAttribute('required','true');
			 $('langMATcomprehensionPercentageMICA').setAttribute('required','true');
			 $('mathskillsMATScoreMICA').setAttribute('required','true');
			 $('mathskillsMATPercentMICA').setAttribute('required','true');
			 $('DASMATScoreMICA').setAttribute('required','true');
			 $('DASMATPercentMICA').setAttribute('required','true');
			 $('reasoningMATScoreMICA').setAttribute('required','true');
			 $('reasoningMATPercentileMICA').setAttribute('required','true');
			 $('IGEMATScoreMICA').setAttribute('required','true');
			 $('IGEMATPercentMICA').setAttribute('required','true');
			
		    }
		    if(obj.value=="XAT"){
		       for(i=1;i<=5;i++)
			    $('xat'+i).style.display = '';
		       
		        $('quantXATScore2013MICA').setAttribute('required','true');
		        $('quantXATPercent2013MICA').setAttribute('required','true');
		        $('ellrMATScore2013MICA').setAttribute('required','true');
		        $('ellrMATPercent2013MICA').setAttribute('required','true');
		        $('decsskillsMATScore2013MICA').setAttribute('required','true');
		        $('decsMATPercent2013MICA').setAttribute('required','true');
		    }
		       if(obj.value=="XAT2014"){
			  for(i=1;i<=5;i++)
			    $('xat'+i+'1').style.display = '';
			
			   $('Xat2014Id').setAttribute('required','true');
			  $('XATScore2014MICA').setAttribute('required','true');
			  $('XATPercent2014MICA').setAttribute('required','true');
			  $('quantXATScore2014MICA').setAttribute('required','true');
			  $('quantXATPercent2014MICA').setAttribute('required','true');
			  $('ellrMATScore2014MICA').setAttribute('required','true');
			  $('ellrMATPercent2014MICA').setAttribute('required','true');
			  $('decsskillsMATScore2014MICA').setAttribute('required','true');
			  $('decsMATPercent2014MICA').setAttribute('required','true');
		    }
	      }
	}
  }
  function setExamFields(objectsArr){
	for(i=0;i<objectsArr.length;i++){
	    document.getElementById(objectsArr[i]).setAttribute('required','true');
	    document.getElementById(objectsArr[i]+'_error').innerHTML = '';
	    document.getElementById(objectsArr[i]+'_error').parentNode.style.display = 'none';
	}
  }

  function resetExamFields(objectsArr){
	for(i=0;i<objectsArr.length;i++){
	    document.getElementById(objectsArr[i]).removeAttribute('required');
	    document.getElementById(objectsArr[i]).value='';
	    document.getElementById(objectsArr[i]+'_error').innerHTML = '';
	    document.getElementById(objectsArr[i]+'_error').parentNode.style.display = 'none';
	}
  }
  
  function changeGaurdianStatus(val){
	if(val=='Father'){
		//$('qualificationFatherMICALI').style.display='';
		if($('guardianNameMICALI'))
			$('guardianNameMICALI').style.display='none';
		if($('occupationGuardianMICALI'))
			$('occupationGuardianMICALI').style.display='none';
		//$('qualificationMotherMICALI').style.display='none';
		if($('qualificationGuardianMICALI'))
			$('qualificationGuardianMICALI').style.display='';
		if($('qualificationGuardianMICA'))
			$('qualificationGuardianMICA').setAttribute('required','true');
		if($('guardianNameMICA'))
			$('guardianNameMICA').removeAttribute('required');
		if($('occupationGuardianMICA'))
			$('occupationGuardianMICA').removeAttribute('required');
		//$('qualificationGuardianMICA').removeAttribute('required');
		//$('qualificationMotherMICA').removeAttribute('required');
		if($('qualificationLabel'))
			$('qualificationLabel').innerHTML = 'Qualification of Father:';
		if($('guardianNameMICA'))
			$('guardianNameMICA').value='<?php echo $fatherNameMICA;?>';
		if($('occupationGuardianMICA'))
			$('occupationGuardianMICA').value='<?php echo $fatherOccupationMICA;?>';
		if($('qualificationGuardianMICA'))
			$('qualificationGuardianMICA').value='';
	}else if(val=='Mother'){
		//$('qualificationFatherMICALI').style.display='';
		if($('guardianNameMICALI'))
			$('guardianNameMICALI').style.display='none';
		if($('occupationGuardianMICALI'))
			$('occupationGuardianMICALI').style.display='none';
		
		//$('qualificationMotherMICALI').style.display='none';
		if($('qualificationGuardianMICALI'))
			$('qualificationGuardianMICALI').style.display='';
		if($('qualificationGuardianMICA'))
			$('qualificationGuardianMICA').setAttribute('required','true');
		if($('guardianNameMICA'))
			$('guardianNameMICA').removeAttribute('required');
		if($('occupationGuardianMICA'))
			$('occupationGuardianMICA').removeAttribute('required');
		//$('qualificationGuardianMICA').removeAttribute('required');
		//$('qualificationMotherMICA').removeAttribute('required');
		if($('qualificationLabel'))
			$('qualificationLabel').innerHTML = 'Qualification of Mother:';
		if($('guardianNameMICA'))
			$('guardianNameMICA').value='<?php echo $motherNameMICA;?>';
	
		if($('occupationGuardianMICA'))
			$('occupationGuardianMICA').value='<?php echo $motherOccupationMICA;?>';
		if($('qualificationGuardianMICA'))
			$('qualificationGuardianMICA').value='';
		
	}else{

		$('guardianNameMICALI').style.display='';
		$('occupationGuardianMICALI').style.display='';
		//$('qualificationFatherMICALI').style.display='none';
		//$('qualificationMotherMICALI').style.display='none';
		$('qualificationGuardianMICALI').style.display='';
		$('guardianNameMICA').setAttribute('required','true');
		$('occupationGuardianMICA').setAttribute('required','true');
		$('qualificationGuardianMICA').setAttribute('required','true');
		//$('qualificationFatherMICA').removeAttribute('required');
		//$('qualificationMotherMICA').removeAttribute('required');
		$('qualificationLabel').innerHTML = 'Qualification of Guardian:';
		if($('occupationGuardianMICA'))
			$('occupationGuardianMICA').value='';
		if($('guardianNameMICA'))
			$('guardianNameMICA').value='';
		if($('qualificationGuardianMICA'))
			$('qualificationGuardianMICA').value='';
	}
  }
  
  function changeJobStatus(val,idIncome,idExp,liId){
	if(val=='Yes'){
		$(liId).style.display='';
		$(idIncome).setAttribute('required','true');
		$(idExp).setAttribute('required','true');
	}else{
		$(liId).style.display='none';
		$(idIncome).removeAttribute('required');
		$(idExp).removeAttribute('required');
		$(idExp).value='';
		$(idIncome).value='';
	}
  }
</script>

<div class='formChildWrapper'>
	<div class='formSection'>
		<ul>
		<?php if($action != 'updateScore'):?>
			<li>
				<h3 class="upperCase">Additional Personal Information</h3>
				<div class='additionalInfoLeftCol'>
				<label>Salutation: </label>
				<div class='fieldBoxLarge'>
				<select name='salutationMICA' id='salutationMICA'    validate="validateSelect"  caption="salutation" tip="Please select the appropriate salutation"     required="true"    onmouseover="showTipOnline('Please select the appropriate salutation',this);" onmouseout="hidetip();" ><option value='' selected>Select</option><option value='Mr'>Mr.</option><option value='Miss' >Miss.</option><option value='Mrs' >Mrs.</option><option value='Dr' >Dr.</option></select>
				<?php if(isset($salutationMICA) && $salutationMICA!=""){ ?>
			      <script>
				  var selObj = document.getElementById("salutationMICA"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $salutationMICA;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'salutationMICA_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Age :</label>
				<div class='fieldBoxLarge'>
				<input type='text' name='ageMICA' id='ageMICA'   required="true"   caption="age" validate="validateInteger"  minlength="2"   maxlength="3"  tip="Please enter your age. The age shall be in numeric digits."   value=''   />
				<?php if(isset($ageMICA) && $ageMICA!=""){ ?>
				  <script>
				      document.getElementById("ageMICA").value = "<?php echo str_replace("\n", '\n', $ageMICA );  ?>";
				      document.getElementById("ageMICA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ageMICA_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol' style="width: 100%">
				<label>Legal Guardian: </label>
				<div class='fieldBoxLarge'  style="width: 300px">
				<input type='radio' name='legalGuardianMICA' id='legalGuardianMICA0'   value='Father'  checked   onmouseover="showTipOnline('Please select who is your legal guardian',this);" onmouseout="hidetip();" onclick="changeGaurdianStatus('Father');"></input><span  onmouseover="showTipOnline('Please select who is your legal guardian',this);" onmouseout="hidetip();" >Father</span>&nbsp;&nbsp;
				<input type='radio' name='legalGuardianMICA' id='legalGuardianMICA1'   value='Mother'     onmouseover="showTipOnline('Please select who is your legal guardian',this);" onmouseout="hidetip();" onclick="changeGaurdianStatus('Mother');"></input><span  onmouseover="showTipOnline('Please select who is your legal guardian',this);" onmouseout="hidetip();" >Mother</span>&nbsp;&nbsp;
				<input type='radio' name='legalGuardianMICA' id='legalGuardianMICA2'   value='Guardian'     onmouseover="showTipOnline('Please select who is your legal guardian',this);" onmouseout="hidetip();" onclick="changeGaurdianStatus('Gaurdian');"></input><span  onmouseover="showTipOnline('Please select who is your legal guardian',this);" onmouseout="hidetip();" >Other</span>&nbsp;&nbsp;
				<?php if(isset($legalGuardianMICA) && $legalGuardianMICA!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["legalGuardianMICA"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $legalGuardianMICA;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'legalGuardianMICA_error'></div></div>
				</div>
				</div>
			</li>
			<?php 
			if($legalGuardianMICA=='Father'){
				$qualificationOfGaurdianLabel = 'Qualification of Father';
				$styleName = 'style="display:none;"';
				$styleOccupation = 'style="display:none;"';
			}else if($legalGuardianMICA=='Mother'){
				$styleName = 'style="display:none;"';
				$styleOccupation = 'style="display:none;"';
				$qualificationOfGaurdianLabel = 'Qualification of Mother';
			}else if($legalGuardianMICA=='Guardian'){
				$styleName = '';
				$styleOccupation = '';
				$qualificationOfGaurdianLabel = 'Qualification of Guardian';
			}else{
				$qualificationOfGaurdianLabel = 'Qualification of Father';
				$styleName = 'style="display:none;"';
				$styleOccupation = 'style="display:none;"';
			}
			?>
			
			
			
			<input type='hidden' id="fatherNameMICA" name='fatherNameMICA' value="<?=$fatherNameMICA?>"/>
			<input type='hidden' id="fatherOccupationMICA" name='fatherOccupationMICA' value="<?=$fatherOccupationMICA?>"/>
			<input type='hidden' id="motherNameMICA" name='motherNameMICA' value="<?=$motherNameMICA?>"/>
			<input type='hidden' id="motherOccupationMICA" name='motherOccupationMICA' value="<?=$motherOccupationMICA?>"/>
			<li id="guardianNameMICALI" <?php echo $styleName;?>>
				<div class='additionalInfoLeftCol'>
				<label id="nameLabel">Name of Guardian: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='guardianNameMICA' id='guardianNameMICA' validate="validateStr" allowNA="true" maxlength="50" minlength="2" caption="name" tip="Please enter the name of your legal guardian.If this is not applicable in your case, just enter 'NA'."   value=''   />
				<?php if(isset($guardianNameMICA) && $guardianNameMICA!=""){?>
				  <script>
				      document.getElementById("guardianNameMICA").value = "<?php echo str_replace("\n", '\n', $guardianNameMICA );  ?>";
				      document.getElementById("guardianNameMICA").style.color = "";
				  </script>
				<?php }else{ ?>
				<?php if($legalGuardianMICA=='Father'){?>
				  <script>
				      document.getElementById("guardianNameMICA").value = "<?php echo str_replace("\n", '\n', $fatherNameMICA );  ?>";
				      document.getElementById("guardianNameMICA").style.color = "";
				  </script>
				<?php } ?>
				<?php if($legalGuardianMICA=='Mother'){?>
				  <script>
				      document.getElementById("guardianNameMICA").value = "<?php echo str_replace("\n", '\n', $motherNameMICA );  ?>";
				      document.getElementById("guardianNameMICA").style.color = "";
				  </script>
				<?php } ?>
				<?php } ?>
				<div style='display:none'><div class='errorMsg' id= 'guardianNameMICA_error'></div></div>
				</div>
				</div>
			</li>
			
			
			<li id="occupationGuardianMICALI" <?php echo $styleOccupation;?>>
				<div class='additionalInfoLeftCol'>
				<label id="occupationLabel">Occupation of Guardian: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='occupationGuardianMICA' id='occupationGuardianMICA' allowNA="true" validate="validateStr" maxlength="50" minlength="2" caption="Occupation" tip="Please enter the occupation of your legal guardian.If this is not applicable in your case, just enter 'NA'."   value=''   />
				<?php if(isset($occupationGuardianMICA) && $occupationGuardianMICA!=""){ ?>
				  <script>
				      document.getElementById("occupationGuardianMICA").value = "<?php echo str_replace("\n", '\n', $occupationGuardianMICA );  ?>";
				      document.getElementById("occupationGuardianMICA").style.color = "";
				  </script>
				<?php }else{ ?>
				<?php if($legalGuardianMICA=='Father'){?>
				  <script>
				      document.getElementById("occupationGuardianMICA").value = "<?php echo str_replace("\n", '\n', $fatherOccupationMICA );  ?>";
				      document.getElementById("occupationGuardianMICA").style.color = "";
				  </script>
				<?php } ?>
				<?php if($legalGuardianMICA=='Mother'){?>
				  <script>
				      document.getElementById("occupationGuardianMICA").value = "<?php echo str_replace("\n", '\n', $motherOccupationMICA );  ?>";
				      document.getElementById("occupationGuardianMICA").style.color = "";
				  </script>
				<?php } ?>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'occupationGuardianMICA_error'></div></div>
				</div>
				</div>	
			</li>
			
			<li id="qualificationGuardianMICALI">
				<div class='additionalInfoLeftCol'>
				<label id="qualificationLabel"><?=$qualificationOfGaurdianLabel;?>: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='qualificationGuardianMICA' id='qualificationGuardianMICA'  allowNA="true" validate="validateStr" maxlength="50" minlength="2" caption="qualification" tip="Please enter the educational qualification of your legal guardian.If this is not applicable in your case, just enter 'NA'."   value=''   />
				<?php if(isset($qualificationGuardianMICA) && $qualificationGuardianMICA!=""){ ?>
				  <script>
				      document.getElementById("qualificationGuardianMICA").value = "<?php echo str_replace("\n", '\n', $qualificationGuardianMICA );  ?>";
				      document.getElementById("qualificationGuardianMICA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'qualificationGuardianMICA_error'></div></div>
				</div>
				</div>
			</li>
			<!--
			<li id="qualificationFatherMICALI" <?php //if($legalGuardianMICA!='Father' && !empty($legalGuardianMICA)){ ?>style="display:none;"<?php //}?>>
				<div class='additionalInfoLeftCol'>
				<label>Qualification of Father: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='qualificationFatherMICA' id='qualificationFatherMICA'  allowNA="true" validate="validateStr" maxlength="50" minlength="2" caption="qualification" tip="Please enter the educational qualification of your legal guardian.If this is not applicable in your case, just enter 'NA'."   value=''   />
				<?php //if(isset($qualificationFatherMICA) && $qualificationFatherMICA!=""){ ?>
				  <script>
				      document.getElementById("qualificationFatherMICA").value = "<?php //echo str_replace("\n", '\n', $qualificationFatherMICA );  ?>";
				      document.getElementById("qualificationFatherMICA").style.color = "";
				  </script>
				<?php //} ?>
				
				<div style='display:none'><div class='errorMsg' id= 'qualificationFatherMICA_error'></div></div>
				</div>
				</div>
			</li>
			
			<li id="qualificationMotherMICALI" <?php //if($legalGuardianMICA!='Mother'){ ?>style="display:none;"<?php //}?>>
				<div class='additionalInfoLeftCol'>
				<label>Qualification of Mother: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='qualificationMotherMICA' id='qualificationMotherMICA' allowNA="true" validate="validateStr" maxlength="50" minlength="2" caption="qualification" tip="Please enter the educational qualification of your legal guardian.If this is not applicable in your case, just enter 'NA'."   value=''   />
				<?php //if(isset($qualificationMotherMICA) && $qualificationMotherMICA!=""){ ?>
				  <script>
				      document.getElementById("qualificationMotherMICA").value = "<?php //echo str_replace("\n", '\n', $qualificationMotherMICA );  ?>";
				      document.getElementById("qualificationMotherMICA").style.color = "";
				  </script>
				<?php //} ?>
				
				<div style='display:none'><div class='errorMsg' id= 'qualificationMotherMICA_error'></div></div>
				</div>
				</div>
			</li>
-->
			<li>
				<div class='additionalInfoLeftCol'>
				<label>Annual Income of Parents (Individually or combined)/ Guardian: </label>
				<div class='fieldBoxLarge'>
				<select name='incomeMICA' id='incomeMICA'   validate="validateSelect"  caption="income"  tip="Please select Annual Income."     required="true"    onmouseover="showTipOnline('Please select Annual Income',this);" onmouseout="hidetip();" ><option value='' selected>Select</option><option value='Less than 1 Lac' >Less than 1 Lac</option><option value='Between 1 to 1.50 Lac' >Between 1 to 1.50 Lac</option><option value='Between 1.5 to 3 Lac' >Between 1.5 to 3 Lac</option><option value='Above 3 Lac' >Above 3 Lac</option></select>
				<?php if(isset($incomeMICA) && $incomeMICA!=""){ ?>
			      <script>
				  var selObj = document.getElementById("incomeMICA"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $incomeMICA;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'incomeMICA_error'></div></div>
				</div>
				</div>
			</li>

			
<?php endif; ?>
			

			<li>
				<h3 class="upperCase">Qualifying Exam Additional Detail</h3>
				<div class='additionalInfoLeftCol' style="width:925px">
				<label>TESTS: </label>
				<div class='fieldBoxLarge' style="width:600px">
				<input onClick="checkTestScore(this);" type='checkbox' name='testNamesMICA[]' id='testNamesMICA0'   value='CAT'  onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date, percentile and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date, percentile and score (if available)',this);" onmouseout="hidetip();" >CAT 2014</span>&nbsp;&nbsp;
				<input onClick="checkTestScore(this);" type='checkbox' name='testNamesMICA[]' id='testNamesMICA1'   value='GMAT'     onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date, percentile and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date, percentile and score (if available)',this);" onmouseout="hidetip();" >GMAT 2011 Onwards</span>&nbsp;&nbsp;
				
			    	<input onClick="checkTestScore(this);" type='checkbox' name='testNamesMICA[]' id='testNamesMICA2'   value='XAT'     onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date, percentile and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date, percentile and score (if available)',this);" onmouseout="hidetip();" >XAT 2014</span>&nbsp;&nbsp;
				<input onClick="checkTestScore(this);" type='checkbox' name='testNamesMICA[]' id='testNamesMICA6'   value='XAT2014'     onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date, percentile and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date, percentile and score (if available)',this);" onmouseout="hidetip();" >XAT 2015</span>&nbsp;&nbsp;
				<input onClick="checkTestScore(this);" type='checkbox' name='testNamesMICA[]' id='testNamesMICA5'   value='MAT'     onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date, percentile and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date, percentile and score (if available)',this);" onmouseout="hidetip();" >MAT 2014</span>&nbsp;&nbsp;<br/>
				<input onClick="checkTestScore(this);" type='checkbox' name='testNamesMICA[]' id='testNamesMICA4'   value='CMAT'     onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date, percentile and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date, percentile and score (if available)',this);" onmouseout="hidetip();" >CMAT (Feb/Sept 2014)</span>&nbsp;&nbsp;
				<input onClick="checkTestScore(this);" type='checkbox' name='testNamesMICA[]' id='testNamesMICA3'   value='ATMA'     onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date, percentile and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date, percentile and score (if available)',this);" onmouseout="hidetip();" >ATMA 2014</span>&nbsp;&nbsp;
				<?php if(isset($testNamesMICA) && $testNamesMICA!=""){   ?>
				
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["testNamesMICA[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$testNamesMICA);
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
				
				<div style='display:none'><div class='errorMsg' id= 'testNamesMICA_error'></div></div>
				</div>
				</div>
			</li>

			
			<li id="cat1" style="display:none;">
				<h3 class="upperCase">CAT 2014 Score (Please enter your CAT 2014 score)</h3>
				<div class='additionalInfoLeftCol'>
				<label>CAT 2014 Registration Number</label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='catRollNumberAdditional' id='catRollNumberAdditional'  validate="validateStr"    caption="roll number"   minlength="2"   maxlength="50"        tip="Please enter CAT 2014 Registration Number."   value=''   />
				<?php if(isset($catRollNumberAdditional) && $catRollNumberAdditional!=""){ ?>
				  <script>
				      document.getElementById("catRollNumberAdditional").value = "<?php echo str_replace("\n", '\n', $catRollNumberAdditional );  ?>";
				      document.getElementById("catRollNumberAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'catRollNumberAdditional_error'></div></div>
				</div>
				</div>
				<!--<div class='additionalInfoRightCol'>
				<label>CAT Percentile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='catPercentileAdditional' id='catPercentileAdditional'   validate="validateFloat"    caption="percentile"   minlength="2"   maxlength="8"    tip="Mention your percentile in the exam. If you don't know your percentile, enter NA."   value=''  allowNA="true" />
				<?php //if(isset($catPercentileAdditional) && $catPercentileAdditional!=""){ ?>
				  <script>
				      document.getElementById("catPercentileAdditional").value = "<?php echo str_replace("\n", '\n', $catPercentileAdditional );  ?>";
				      document.getElementById("catPercentileAdditional").style.color = "";
				  </script>
				<?php //} ?>
				
				<div style='display:none'><div class='errorMsg' id= 'catPercentileAdditional_error'></div></div>
				</div>
				</div>-->

				
				
			</li>
<?php //echo str_replace("\n", '\n', $catDateOfExaminationAdditional);  ?>
			
				<li id="cat3" style="display:none;">
				<div class='additionalInfoLeftCol'>
				<label>CAT 2014 Quantitative Ability and Data Interpretation Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='qadiCATScoreMICA' id='qadiCATScoreMICA'  allowNA="true"  maxlength="5" minlength="1" caption="score" validate="validateFloat"   tip="Please enter your score for Quantitative Ability and Data Interpretation.If this is not applicable in your case, just enter 'NA'."   value=''   />
				<?php if(isset($qadiCATScoreMICA) && $qadiCATScoreMICA!=""){ ?>
				  <script>
				      document.getElementById("qadiCATScoreMICA").value = "<?php echo str_replace("\n", '\n', $qadiCATScoreMICA );  ?>";
				      document.getElementById("qadiCATScoreMICA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'qadiCATScoreMICA_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>CAT 2014 QADI Percentile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='qadiCATPercentileMICA' id='qadiCATPercentileMICA' allowNA="true" maxlength="5" minlength="1" caption="percentile" validate="validateFloat"   tip="Please enter your percentile for Quantitative Ability and Data Interpretation.If this is not applicable in your case, just enter 'NA'."   value=''   />
				<?php if(isset($qadiCATPercentileMICA) && $qadiCATPercentileMICA!=""){ ?>
				  <script>
				      document.getElementById("qadiCATPercentileMICA").value = "<?php echo str_replace("\n", '\n', $qadiCATPercentileMICA );  ?>";
				      document.getElementById("qadiCATPercentileMICA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'qadiCATPercentileMICA_error'></div></div>
				</div>
				</div>
			</li>

			<li id="cat4" style="display:none;  padding-bottom:15px">
				<div class='additionalInfoLeftCol'>
				<label>CAT 2014 Verbal Ability and Logical Reasoning Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='valrCATScoreMICA' id='valrCATScoreMICA' allowNA="true" maxlength="5" minlength="1" caption="score" validate="validateFloat"      tip="Please enter your score for Verbal Ability and Logical Reasoning.If this is not applicable in your case, just enter 'NA'."   value=''   />
				<?php if(isset($valrCATScoreMICA) && $valrCATScoreMICA!=""){ ?>
				  <script>
				      document.getElementById("valrCATScoreMICA").value = "<?php echo str_replace("\n", '\n', $valrCATScoreMICA );  ?>";
				      document.getElementById("valrCATScoreMICA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'valrCATScoreMICA_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>CAT 2014 VALR Percentile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='valrCATPercentileMICA' id='valrCATPercentileMICA' allowNA="true" maxlength="5" minlength="1" caption="percentile" validate="validateFloat"     tip="Please enter your percentile for Verbal Ability and Logical Reasoning.If this is not applicable in your case, just enter 'NA'."   value=''   />
				<?php if(isset($valrCATPercentileMICA) && $valrCATPercentileMICA!=""){ ?>
				  <script>
				      document.getElementById("valrCATPercentileMICA").value = "<?php echo str_replace("\n", '\n', $valrCATPercentileMICA );  ?>";
				      document.getElementById("valrCATPercentileMICA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'valrCATPercentileMICA_error'></div></div>
				</div>
				</div>
			</li>
			
			<li id="cat2" style="display:none;">

				<div class='additionalInfoLeftCol'>
				<label>CAT 2014 Total Score:</label>
				<div class='fieldBoxLarge'>
				<input type='text' name='catScoreAdditional' id='catScoreAdditional'   validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"     tip="Mention your CAT score. If the exam authorities have only declared percentiles for the exam, enter NA."  allowNA="true"   value=''  />
				<?php if(isset($catScoreAdditional) && $catScoreAdditional!=""){ ?>
				  <script>
				      document.getElementById("catScoreAdditional").value = "<?php echo str_replace("\n", '\n', $catScoreAdditional );  ?>";
				      document.getElementById("catScoreAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'catScoreAdditional_error'></div></div>
				</div>
				</div>
				<div class='additionalInfoRightCol'>
				<label>CAT 2014 Total Percentile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='catPercentileAdditional' id='catPercentileAdditional'   validate="validateFloat"    caption="percentile"   minlength="2"   maxlength="8"    tip="Mention your CAT percentile in the exam. If you don't know your percentile, enter NA."   value=''  allowNA="true" />
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
			<?php
			    if(isset($testNamesMICA) && $testNamesMICA!="" && strpos($testNamesMICA,'CAT')!==false){ ?>
			    <script>
				    checkTestScore(document.getElementById('testNamesMICA0'));
			    </script>
			<?php
			    }
			?>
		   
			<li id="gmat1" style="display:none;" >
			   <h3 class="upperCase">GMAT Score (Please enter your GMAT score of 2011 onwards)</h3>
		
				<div class='additionalInfoLeftCol'>
				<label>GMAT Test Date: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gmatDateOfExaminationAdditional' id='gmatDateOfExaminationAdditional' readonly maxlength='10'     validate="validateDateForms"  caption="date"     tip="Mention the date on which gmat examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('gmatDateOfExaminationAdditional'),'gmatDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />
				&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='gmatDateOfExaminationAdditional_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('gmatDateOfExaminationAdditional'),'gmatDateOfExaminationAdditional_dateImg','dd/MM/yyyy'); return false;" />
				<?php if(isset($gmatDateOfExaminationAdditional) && $gmatDateOfExaminationAdditional!=""){ ?>
				  <script>
				      document.getElementById("gmatDateOfExaminationAdditional").value = "<?php echo str_replace("\n", '\n', $gmatDateOfExaminationAdditional );  ?>";
				      document.getElementById("gmatDateOfExaminationAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gmatDateOfExaminationAdditional_error'></div></div>
				</div>
				</div>

				<div class="additionalInfoRightCol">
				<label style="font-weight:normal">GMAT Roll No.: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='gmatRollNumberAdditional' id='gmatRollNumberAdditional'  validate="validateStr"  allowNA="true"  caption="Roll Number"   minlength="1"   maxlength="20"     tip="Mention your roll number for the gmat exam. If you do not have the roll number, enter NA"   value=''  />
				<?php if(isset($gmatRollNumberAdditional) && $gmatRollNumberAdditional!=""){ ?>
						<script>
						document.getElementById("gmatRollNumberAdditional").value = "<?php echo str_replace("\n", '\n', $gmatRollNumberAdditional );  ?>";
						document.getElementById("gmatRollNumberAdditional").style.color = "";
						</script>
					  <?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'gmatRollNumberAdditional_error'></div></div>
				</div>
			</div>
				<!----div class='additionalInfoRightCol'>
				<label>GMAT Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gmatScoreAdditional' id='gmatScoreAdditional'  validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"        tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter NA."   value=''  allowNA="true" />
				<?php // if(isset($gmatScoreAdditional) && $gmatScoreAdditional!=""){ ?>
				  <script>
				      document.getElementById("gmatScoreAdditional").value = "<?php //echo str_replace("\n", '\n', $gmatScoreAdditional );  ?>";
				      document.getElementById("gmatScoreAdditional").style.color = "";
				  </script>
				<?php // } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gmatScoreAdditional_error'></div></div>
				</div>
				</div>-->
				
			</li>
			
		
			
			<li id="gmat3" style="display:none;">
				<div class='additionalInfoLeftCol'>
				<label>Verbal: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='verbalGMATScoreMICA' id='verbalGMATScoreMICA'  allowNA="true"  maxlength="5" minlength="1" caption="score" validate="validateFloat"   tip="Please enter your verbal score.If this is not applicable in your case, just enter 'NA'."   value=''   />
				<?php if(isset($verbalGMATScoreMICA) && $verbalGMATScoreMICA!=""){ ?>
				  <script>
				      document.getElementById("verbalGMATScoreMICA").value = "<?php echo str_replace("\n", '\n', $verbalGMATScoreMICA );  ?>";
				      document.getElementById("verbalGMATScoreMICA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'verbalGMATScoreMICA_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Verbal Percentage: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='verbalGMATPercentMICA' id='verbalGMATPercentMICA' allowNA="true" maxlength="5" minlength="1" caption="percentile" validate="validateFloat"   tip="Please enter your verbal percentage.If this is not applicable in your case, just enter 'NA'."   value=''   />
				<?php if(isset($verbalGMATPercentMICA) && $verbalGMATPercentMICA!=""){ ?>
				  <script>
				      document.getElementById("verbalGMATPercentMICA").value = "<?php echo str_replace("\n", '\n', $verbalGMATPercentMICA );  ?>";
				      document.getElementById("verbalGMATPercentMICA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'verbalGMATPercentMICA_error'></div></div>
				</div>
				</div>
			</li>

			<li id="gmat4" style="display:none;">
				<div class='additionalInfoLeftCol'>
				<label>Quantitative : </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='quantGMATScoreMICA' id='quantGMATScoreMICA' allowNA="true" maxlength="5" minlength="1" caption="score" validate="validateFloat"      tip="Please enter your quantitative score.If this is not applicable in your case, just enter 'NA'."   value=''   />
				<?php if(isset($quantGMATScoreMICA) && $quantGMATScoreMICA!=""){ ?>
				  <script>
				      document.getElementById("quantGMATScoreMICA").value = "<?php echo str_replace("\n", '\n', $quantGMATScoreMICA );  ?>";
				      document.getElementById("quantGMATScoreMICA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'quantGMATScoreMICA_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Quantitative Percentage: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='quantGMATPercentMICA' id='quantGMATPercentMICA' allowNA="true" maxlength="5" minlength="1" caption="score" validate="validateFloat"      tip="Please enter your quantitative percentage.If this is not applicable in your case, just enter 'NA'."   value=''   />
				<?php if(isset($quantGMATPercentMICA) && $quantGMATPercentMICA!=""){ ?>
				  <script>
				      document.getElementById("quantGMATPercentMICA").value = "<?php echo str_replace("\n", '\n', $quantGMATPercentMICA );  ?>";
				      document.getElementById("quantGMATPercentMICA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'quantGMATPercentMICA_error'></div></div>
				</div>
				</div>
			</li>
			
			<li id="gmat5" style="display:none;">
				<div class='additionalInfoLeftCol'>
				<label>Analytical Writing : </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='analyticalGMATScoreMICA' id='analyticalGMATScoreMICA'  allowNA="true"  maxlength="5" minlength="1" caption="score" validate="validateFloat"   tip="Please enter your analytical writing score.If this is not applicable in your case, just enter 'NA'."   value=''   />
				<?php if(isset($analyticalGMATScoreMICA) && $analyticalGMATScoreMICA!=""){ ?>
				  <script>
				      document.getElementById("analyticalGMATScoreMICA").value = "<?php echo str_replace("\n", '\n', $analyticalGMATScoreMICA );  ?>";
				      document.getElementById("analyticalGMATScoreMICA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'analyticalGMATScoreMICA_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Analytical Writing Percentage: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='analyticalGMATPercentMICA' id='analyticalGMATPercentMICA' allowNA="true" maxlength="5" minlength="1" caption="percentile" validate="validateFloat"   tip="Please enter your analytical writing percentage.If this is not applicable in your case, just enter 'NA'."   value=''   />
				<?php if(isset($analyticalGMATPercentMICA) && $analyticalGMATPercentMICA!=""){ ?>
				  <script>
				      document.getElementById("analyticalGMATPercentMICA").value = "<?php echo str_replace("\n", '\n', $analyticalGMATPercentMICA );  ?>";
				      document.getElementById("analyticalGMATPercentMICA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'analyticalGMATPercentMICA_error'></div></div>
				</div>
				</div>
			</li>
			
			<li id="gmat6" style=" display:none; padding-bottom:15px">
				<div class='additionalInfoLeftCol'>
				<label>Integrated Reasoning : </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='reasoningGMATScoreMICA' id='reasoningGMATScoreMICA'  allowNA="true"  maxlength="5" minlength="1" caption="score" validate="validateFloat"   tip="Please enter your Integrated Reasoning score.If this is not applicable in your case, just enter 'NA'."   value=''   />
				<?php if(isset($reasoningGMATScoreMICA) && $reasoningGMATScoreMICA!=""){ ?>
				  <script>
				      document.getElementById("reasoningGMATScoreMICA").value = "<?php echo str_replace("\n", '\n', $reasoningGMATScoreMICA );  ?>";
				      document.getElementById("reasoningGMATScoreMICA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'reasoningGMATScoreMICA_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Integrated Reasoning Percentage: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='reasoningGMATPercentMICA' id='reasoningGMATPercentMICA' allowNA="true" maxlength="5" minlength="1" caption="percentile" validate="validateFloat"   tip="Please enter your Integrated Reasoning percentage.If this is not applicable in your case, just enter 'NA'."   value=''   />
				<?php if(isset($reasoningGMATPercentMICA) && $reasoningGMATPercentMICA!=""){ ?>
				  <script>
				      document.getElementById("reasoningGMATPercentMICA").value = "<?php echo str_replace("\n", '\n', $reasoningGMATPercentMICA );  ?>";
				      document.getElementById("reasoningGMATPercentMICA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'reasoningGMATPercentMICA_error'></div></div>
				</div>
				</div>
			</li>
			
			      <li id="gmat2" style="display:none;">
			
				<div class='additionalInfoLeftCol'>
				<label>Total: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gmatScoreAdditional' id='gmatScoreAdditional'  validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"        tip="Please enter your GMAT score of 2010 onwards."   value=''  allowNA="true" />
				<?php if(isset($gmatScoreAdditional) && $gmatScoreAdditional!=""){ ?>
				  <script>
				      document.getElementById("gmatScoreAdditional").value = "<?php echo str_replace("\n", '\n', $gmatScoreAdditional );  ?>";
				      document.getElementById("gmatScoreAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gmatScoreAdditional_error'></div></div>
				</div>
			</div>
			
			<div class="additionalInfoRightCol">
				<label>Total Percentage: </label>
				<div class='float_L'>
				   <input class="textboxSmaller"  type='text' name='gmatPercentileAdditional' id='gmatPercentileAdditional'  validate="validateFloat"  allowNA="true"  caption="Percentile"   minlength="1"   maxlength="5"     tip="Mention your percentage for the exam. If you don't know your percentage, enter NA."   value=''  />
					<?php if(isset($gmatPercentileAdditional) && $gmatPercentileAdditional!=""){ ?>
							<script>
							document.getElementById("gmatPercentileAdditional").value = "<?php echo str_replace("\n", '\n', $gmatPercentileAdditional );  ?>";
							document.getElementById("gmatPercentileAdditional").style.color = "";
							</script>
						  <?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'gmatPercentileAdditional_error'></div></div>
				</div>
			</div>
			
			</li>
			<?php
			    if(isset($testNamesMICA) && $testNamesMICA!="" && strpos($testNamesMICA,'GMAT')!==false){ ?>
			    <script>
				    checkTestScore(document.getElementById('testNamesMICA1'));
			    </script>
			<?php
			    }
			?>
			
		  <li id="xat1" style="display:none;">
		  <h3 class="upperCase">XAT 2014 Score(Please enter your XAT 2014 score)</h3>
			<div class="clearFix"></div>
			<div class="additionalInfoLeftCol">
				<label>XAT 2014 ID: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='xatRollNumberAdditional' id='xatRollNumberAdditional'  validate="validateStr"   allowNA="true"  caption="Roll Number"   minlength="1"   maxlength="20"     tip="Enter your XAT 2014 ID"   value=''  />
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
		
		
		<li id="xat3" style="display:none; " >
				<div class='additionalInfoLeftCol'>
				<label>XAT 2014 Quantitative Ability:</label>
				<div class='fieldBoxLarge'>
				<input type='text' name='quantXATScore2013MICA' id='quantXATScore2013MICA'  allowNA="true"  maxlength="5" minlength="1" caption="score" validate="validateFloat"   tip="Please enter your Quantitative Ability score.If this is not applicable in your case, just enter 'NA'."   value=''   />
				<?php if(isset($quantXATScore2013MICA) && $quantXATScore2013MICA!=""){ ?>
				  <script>
				      document.getElementById("quantXATScore2013MICA").value = "<?php echo str_replace("\n", '\n', $quantXATScore2013MICA );  ?>";
				      document.getElementById("quantXATScore2013MICA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'quantXATScore2013MICA_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>XAT 2014 Quantitative Ability Percentile:</label>
				<div class='fieldBoxLarge'>
				<input type='text' name='quantXATPercent2013MICA' id='quantXATPercent2013MICA' allowNA="true" maxlength="5" minlength="1" caption="percentage" validate="validateFloat"   tip="Please enter your Quantitative Ability percentile.If this is not applicable in your case, just enter 'NA'."   value=''   />
				<?php if(isset($quantXATPercent2013MICA) && $quantXATPercent2013MICA!=""){ ?>
				  <script>
				      document.getElementById("quantXATPercent2013MICA").value = "<?php echo str_replace("\n", '\n', $quantXATPercent2013MICA );  ?>";
				      document.getElementById("quantXATPercent2013MICA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'quantXATPercent2013MICA_error'></div></div>
				</div>
				</div>
			</li>
			
			<li id="xat4" style=" display: none;">
				<div class='additionalInfoLeftCol'>
				<label>XAT 2014 English Language and Logical Reasoning :</label>
				<div class='fieldBoxLarge'>
				<input type='text' name='ellrMATScore2013MICA' id='ellrMATScore2013MICA'  allowNA="true"  maxlength="5" minlength="1" caption="score" validate="validateFloat"   tip="Please enter your English Language and Logical Reasoning Score.If this is not applicable in your case, just enter 'NA'."   value=''   />
				<?php if(isset($ellrMATScore2013MICA) && $ellrMATScore2013MICA!=""){ ?>
				  <script>
				      document.getElementById("ellrMATScore2013MICA").value = "<?php echo str_replace("\n", '\n', $ellrMATScore2013MICA );  ?>";
				      document.getElementById("ellrMATScore2013MICA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ellrMATScore2013MICA_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>XAT 2014 ELLR Percentile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='ellrMATPercent2013MICA' id='ellrMATPercent2013MICA' allowNA="true" maxlength="5" minlength="1" caption="percentage" validate="validateFloat"   tip="Please enter your English Language and Logical Reasoning percentage.If this is not applicable in your case, just enter 'NA'."   value=''   />
				<?php if(isset($ellrMATPercent2013MICA) && $ellrMATPercent2013MICA!=""){ ?>
				  <script>
				      document.getElementById("ellrMATPercent2013MICA").value = "<?php echo str_replace("\n", '\n', $ellrMATPercent2013MICA );  ?>";
				      document.getElementById("ellrMATPercent2013MICA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ellrMATPercent2013MICA_error'></div></div>
				</div>
				</div>
			</li>
			
			<li id="xat5" style=" display:none; padding-bottom:15px">
				<div class='additionalInfoLeftCol'>
				<label>XAT 2014 Decision Making Score :</label>
				<div class='fieldBoxLarge'>
				<input type='text' name='decsskillsMATScore2013MICA' id='decsskillsMATScore2013MICA'  allowNA="true"  maxlength="5" minlength="1" caption="score" validate="validateFloat"   tip="Please enter your Decision Making Score.If this is not applicable in your case, just enter 'NA'."   value=''   />
				<?php if(isset($decsskillsMATScore2013MICA) && $decsskillsMATScore2013MICA!=""){ ?>
				  <script>
				      document.getElementById("decsskillsMATScore2013MICA").value = "<?php echo str_replace("\n", '\n', $decsskillsMATScore2013MICA );  ?>";
				      document.getElementById("decsskillsMATScore2013MICA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'decsskillsMATScore2013MICA_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>XAT 2014 Decsion Making Percentile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='decsMATPercent2013MICA' id='decsMATPercent2013MICA' allowNA="true" maxlength="5" minlength="1" caption="percentage" validate="validateFloat"   tip="Please enter your Decision Making Percentage.If this is not applicable in your case, just enter 'NA'."   value=''   />
				<?php if(isset($decsMATPercent2013MICA) && $decsMATPercent2013MICA!=""){ ?>
				  <script>
				      document.getElementById("decsMATPercent2013MICA").value = "<?php echo str_replace("\n", '\n', $decsMATPercent2013MICA );  ?>";
				      document.getElementById("decsMATPercent2013MICA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'decsMATPercent2013MICA_error'></div></div>
				</div>
				</div>
			</li>
			       <li id="xat2" style="display:none; ">
		      	
			
			<div class="additionalInfoLeftCol">
				<label>XAT 2014 Total : </label>
				<div class='float_L'>
					<input class="textboxSmaller" type='text' name='xatScoreAdditional' id='xatScoreAdditional'  validate="validateFloat"  allowNA="true"  caption="Score"   minlength="1"   maxlength="5"     tip="Mention how much you scored in the XAT exam. If the exam authorities have only declared percentiles for the exam, enter NA."   value=''  />
					<?php if(isset($xatScoreAdditional) && $xatScoreAdditional!=""){ ?>
							<script>
								document.getElementById("xatScoreAdditional").value = "<?php echo str_replace("\n", '\n', $xatScoreAdditional );  ?>";
								document.getElementById("xatScoreAdditional").style.color = "";
							</script>
							  <?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'xatScoreAdditional_error'></div></div>
				</div>
			</div>
			
			<div class="additionalInfoRightCol">
				<label>XAT 2014 Total Percentile: </label>
				<div class='float_L'>
				   <input class="textboxSmaller"  type='text' name='xatPercentileAdditional' id='xatPercentileAdditional'  validate="validateFloat" allowNA="true"  caption="Percentile"   minlength="1"   maxlength="5"     tip="Mention your percentile in the exam. If you don't know your percentile, enter NA."   value=''  />
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
		
			
		<?php
		if(isset($testNamesMICA) && $testNamesMICA!="" && strpos($testNamesMICA,'XAT')!==false){ ?>
		<script>
			checkTestScore(document.getElementById('testNamesMICA2'));
		</script>
		<?php
		    }
		?>
		
		
		<li id="xat11" style="display:none;">
		<h3 class="upperCase">XAT 2015 (Please enter your XAT 2015 ID)</h3>
			<div class="clearFix"></div>
			<div class="additionalInfoLeftCol">
				<label>XAT 2015 ID: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='Xat2014Id' id='Xat2014Id'  validate="validateStr"   allowNA="true"  caption="Roll Number"   minlength="1"   maxlength="20"     tip="Enter your XAT 2015 Id."   value=''  />
				<?php if(isset($Xat2014Id) && $Xat2014Id!=""){ ?>
						<script>
						document.getElementById("Xat2014Id").value = "<?php echo str_replace("\n", '\n', $Xat2014Id );  ?>";
						document.getElementById("Xat2014Id").style.color = "";
						</script>
					  <?php } ?>
				<div style='display:none'><div class='errorMsg' id= 'Xat2014Id_error'></div></div>
				</div>
			</div>
			
			
		</li>
		
		
		<!---
		<li id="xat31" style="display:none; " >
				<div class='additionalInfoLeftCol'>
				<label>XAT 2015 Quantitative Ability :</label>
				<div class='fieldBoxLarge'>
				<input type='text' name='quantXATScore2014MICA' id='quantXATScore2014MICA'  allowNA="true"  maxlength="5" minlength="1" caption="score" validate="validateFloat"   tip="Please enter your XAT 2015 Quantitative Ability score.If this is not applicable in your case, just enter 'NA'."   value=''   />
				<?php if(isset($quantXATScore2014MICA) && $quantXATScore2014MICA!=""){ ?>
				  <script>
				      document.getElementById("quantXATScore2014MICA").value = "<?php echo str_replace("\n", '\n', $quantXATScore2014MICA );  ?>";
				      document.getElementById("quantXATScore2014MICA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'quantXATScore2014MICA_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>XAT 2015 Quantitative Ability Percentile:</label>
				<div class='fieldBoxLarge'>
				<input type='text' name='quantXATPercent2014MICA' id='quantXATPercent2014MICA' allowNA="true" maxlength="5" minlength="1" caption="percentile" validate="validateFloat"   tip="Please enter your XAT 2015 Quantitative Ability percentile.If this is not applicable in your case, just enter 'NA'."   value=''   />
				<?php if(isset($quantXATPercent2014MICA) && $quantXATPercent2014MICA!=""){ ?>
				  <script>
				      document.getElementById("quantXATPercent2014MICA").value = "<?php echo str_replace("\n", '\n', $quantXATPercent2014MICA );  ?>";
				      document.getElementById("quantXATPercent2014MICA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'quantXATPercent2014MICA_error'></div></div>
				</div>
				</div>
			</li>
			
			<li id="xat41" style=" display: none;">
				<div class='additionalInfoLeftCol'>
				<label>XAT 2015 English Language and Logical Reasoning :</label>
				<div class='fieldBoxLarge'>
				<input type='text' name='ellrMATScore2014MICA' id='ellrMATScore2014MICA'  allowNA="true"  maxlength="5" minlength="1" caption="score" validate="validateFloat"   tip="Please enter your English Language and Logical Reasoning Score .If this is not applicable in your case, just enter 'NA'."   value=''   />
				<?php if(isset($ellrMATScore2014MICA) && $ellrMATScore2014MICA!=""){ ?>
				  <script>
				      document.getElementById("ellrMATScore2014MICA").value = "<?php echo str_replace("\n", '\n', $ellrMATScore2014MICA );  ?>";
				      document.getElementById("ellrMATScore2014MICA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ellrMATScore2014MICA_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>XAT 2015  ELLR Percentile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='ellrMATPercent2014MICA' id='ellrMATPercent2014MICA' allowNA="true" maxlength="5" minlength="1" caption="percentile" validate="validateFloat"   tip="Please enter your English Language and Logical Reasoning percentile.If this is not applicable in your case, just enter 'NA'."   value=''   />
				<?php if(isset($ellrMATPercent2014MICA) && $ellrMATPercent2014MICA!=""){ ?>
				  <script>
				      document.getElementById("ellrMATPercent2014MICA").value = "<?php echo str_replace("\n", '\n', $ellrMATPercent2014MICA );  ?>";
				      document.getElementById("ellrMATPercent2014MICA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ellrMATPercent2014MICA_error'></div></div>
				</div>
				</div>
			</li>
			
			<li id="xat51" style="display: none;  padding-bottom:15px">
				<div class='additionalInfoLeftCol'>
				<label>XAT 2015 Decision Making :</label>
				<div class='fieldBoxLarge'>
				<input type='text' name='decsskillsMATScore2014MICA' id='decsskillsMATScore2014MICA'  allowNA="true"  maxlength="5" minlength="1" caption="score" validate="validateFloat"   tip="Please enter your Decision Making score.If this is not applicable in your case, just enter 'NA'."   value=''   />
				<?php if(isset($decsskillsMATScore2014MICA) && $decsskillsMATScore2014MICA!=""){ ?>
				  <script>
				      document.getElementById("decsskillsMATScore2014MICA").value = "<?php echo str_replace("\n", '\n', $decsskillsMATScore2014MICA );  ?>";
				      document.getElementById("decsskillsMATScore2014MICA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'decsskillsMATScore2014MICA_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>XAT 2015 Decsion Making Percentile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='decsMATPercent2014MICA' id='decsMATPercent2014MICA' allowNA="true" maxlength="5" minlength="1" caption="percentile" validate="validateFloat"   tip="Please enter your Decision Making percentile.If this is not applicable in your case, just enter 'NA'."   value=''   />
				<?php if(isset($decsMATPercent2014MICA) && $decsMATPercent2014MICA!=""){ ?>
				  <script>
				      document.getElementById("decsMATPercent2014MICA").value = "<?php echo str_replace("\n", '\n', $decsMATPercent2014MICA );  ?>";
				      document.getElementById("decsMATPercent2014MICA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'decsMATPercent2014MICA_error'></div></div>
				</div>
				</div>
			</li>
			<li id="xat21" style="display:none; ">
		
			<div class="additionalInfoLeftCol">
				<label>XAT 2015 Total :</label>
				<div class='float_L'>
					<input class="textboxSmaller" type='text' name='XATScore2014MICA' id='XATScore2014MICA'  validate="validateFloat"  allowNA="true"  caption="Score"   minlength="1"   maxlength="5"     tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter NA."   value=''  />
					<?php if(isset($XATScore2014MICA) && $XATScore2014MICA!=""){ ?>
							<script>
								document.getElementById("XATScore2014MICA").value = "<?php echo str_replace("\n", '\n', $XATScore2014MICA );  ?>";
								document.getElementById("XATScore2014MICA").style.color = "";
							</script>
							  <?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'XATScore2014MICA_error'></div></div>
				</div>
			</div>
			
			
			<div class="additionalInfoRightCol">
				<label>XAT Total Percentile: </label>
				<div class='float_L'>
				   <input class="textboxSmaller"  type='text' name='XATPercent2014MICA' id='XATPercent2014MICA'  validate="validateFloat" allowNA="true"  caption="Percentile"   minlength="1"   maxlength="5"     tip="Mention your percentile in the exam. If you don't know your percentile, enter NA."   value=''  />
					<?php if(isset($XATPercent2014MICA) && $XATPercent2014MICA!=""){ ?>
							<script>
							document.getElementById("XATPercent2014MICA").value = "<?php echo str_replace("\n", '\n', $XATPercent2014MICA );  ?>";
							document.getElementById("XATPercent2014MICA").style.color = "";
							</script>
						  <?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'XATPercent2014MICA_error'></div></div>
				</div>
			</div>
			
		</li>
		-->
		<?php
		if(isset($testNamesMICA) && $testNamesMICA!="" && strpos($testNamesMICA,'XAT2014')!==false){ ?>
		<script>
			checkTestScore(document.getElementById('testNamesMICA6'));
		</script>
		<?php
		    }
		?>
		
		
			<li id="mat3" style="display:none;"><h3 class="upperCase">MAT 2014 (Please enter your MAT February 2014 / May 2014 / September 2014 or December 2014 Score)</h3>
				<div class='additionalInfoLeftCol'>
				<label> FORM NUMBER :</label>
				<div class='fieldBoxLarge'>
				<input type='text' name='formMATnumberMICA' id='formMATnumberMICA' allowNA="true" maxlength="20" minlength="1" caption="Form Number" validate="validateFloat"      tip="Please enter your form number.If this is not applicable in your case, just enter 'NA'."   value=''   />
				<?php if(isset($formMATnumberMICA) && $formMATnumberMICA!=""){ ?>
				  <script>
				      document.getElementById("formMATnumberMICA").value = "<?php echo str_replace("\n", '\n', $formMATnumberMICA );  ?>";
				      document.getElementById("formMATnumberMICA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'formMATnumberMICA_error'></div></div>
				</div>
				</div>
			
			      <div class="additionalInfoRightCol">
				<label style="font-weight:normal">ROLL NUMBER: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='matRollNumberAdditional' id='matRollNumberAdditional'  validate="validateStr"  allowNA="true"  caption="Roll Number"   minlength="1"   maxlength="20"     tip="Mention your roll number for the MAT exam. If you do not have the roll number, enter NA"   value=''  />
				<?php if(isset($matRollNumberAdditional) && $matRollNumberAdditional!=""){ ?>
						<script>
						document.getElementById("matRollNumberAdditional").value = "<?php echo str_replace("\n", '\n', $matRollNumberAdditional );  ?>";
						document.getElementById("matRollNumberAdditional").style.color = "";
						</script>
					  <?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'matRollNumberAdditional_error'></div></div>
				</div>
			</div>
			
			</li>
			
			<li id="mat1" style="display:none;">
			  
			
				<div class='additionalInfoLeftCol'>
				<label>MAT 2014 TEST DATE: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='matDateOfExaminationAdditional' id='matDateOfExaminationAdditional' readonly maxlength='10'     validate="validateDateForms"  caption="date"     tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('matDateOfExaminationAdditional'),'matDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />
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
			
		   
			

			<li id="mat4" style="display:none;">
				<div class='additionalInfoLeftCol'>
				<label>MAT 2014 Language Comprehension: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='langMATcomprehensionScoreMICA' id='langMATcomprehensionScoreMICA' allowNA="true" maxlength="5" minlength="1" caption="score" validate="validateFloat"      tip="Please enter your Language Comprehension score.If this is not applicable in your case, just enter 'NA'."   value=''   />
				<?php if(isset($langMATcomprehensionScoreMICA) && $langMATcomprehensionScoreMICA!=""){ ?>
				  <script>
				      document.getElementById("langMATcomprehensionScoreMICA").value = "<?php echo str_replace("\n", '\n', $langMATcomprehensionScoreMICA );  ?>";
				      document.getElementById("langMATcomprehensionScoreMICA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'langMATcomprehensionScoreMICA_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>MAT 2014 Language Comprehension Percentage : </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='langMATcomprehensionPercentageMICA' id='langMATcomprehensionPercentageMICA' allowNA="true" maxlength="5" minlength="1" caption="score" validate="validateFloat"      tip="Please enter your Language Comprehension Percentage.If this is not applicable in your case, just enter 'NA'."   value=''   />
				<?php if(isset($langMATcomprehensionPercentageMICA) && $langMATcomprehensionPercentageMICA!=""){ ?>
				  <script>
				      document.getElementById("langMATcomprehensionPercentageMICA").value = "<?php echo str_replace("\n", '\n', $langMATcomprehensionPercentageMICA );  ?>";
				      document.getElementById("langMATcomprehensionPercentageMICA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'langMATcomprehensionPercentageMICA_error'></div></div>
				</div>
				</div>
			</li>
			
			<li id="mat5" style="display:none;">
				<div class='additionalInfoLeftCol'>
				<label>Mathematical Skills : </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='mathskillsMATScoreMICA' id='mathskillsMATScoreMICA'  allowNA="true"  maxlength="5" minlength="1" caption="score" validate="validateFloat"   tip="Please enter your Mathematical skills score.If this is not applicable in your case, just enter 'NA'."   value=''   />
				<?php if(isset($mathskillsMATScoreMICA) && $mathskillsMATScoreMICA!=""){ ?>
				  <script>
				      document.getElementById("mathskillsMATScoreMICA").value = "<?php echo str_replace("\n", '\n', $mathskillsMATScoreMICA );  ?>";
				      document.getElementById("mathskillsMATScoreMICA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'mathskillsMATScoreMICA_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Mathematical Skills Percentage: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='mathskillsMATPercentMICA' id='mathskillsMATPercentMICA' allowNA="true" maxlength="5" minlength="1" caption="percentage" validate="validateFloat"   tip="Please enter your Mathematical Skills percentage.If this is not applicable in your case, just enter 'NA'."   value=''   />
				<?php if(isset($mathskillsMATPercentMICA) && $mathskillsMATPercentMICA!=""){ ?>
				  <script>
				      document.getElementById("mathskillsMATPercentMICA").value = "<?php echo str_replace("\n", '\n', $mathskillsMATPercentMICA );  ?>";
				      document.getElementById("mathskillsMATPercentMICA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'mathskillsMATPercentMICA_error'></div></div>
				</div>
				</div>
			</li>
			
			<li id="mat6" style=" display: none;">
				<div class='additionalInfoLeftCol'>
				<label>Data Analysis and Sufficiency:</label>
				<div class='fieldBoxLarge'>
				<input type='text' name='DASMATScoreMICA' id='DASMATScoreMICA'  allowNA="true"  maxlength="5" minlength="1" caption="score" validate="validateFloat"   tip="Please enter your Data Analysis and Sufficiency Score.If this is not applicable in your case, just enter 'NA'."   value=''   />
				<?php if(isset($DASMATScoreMICA) && $DASMATScoreMICA!=""){ ?>
				  <script>
				      document.getElementById("DASMATScoreMICA").value = "<?php echo str_replace("\n", '\n', $DASMATScoreMICA );  ?>";
				      document.getElementById("DASMATScoreMICA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'DASMATScoreMICA_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>DAS  Percentage: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='DASMATPercentMICA' id='DASMATPercentMICA' allowNA="true" maxlength="5" minlength="1" caption="percentage" validate="validateFloat"   tip="Please enter your Data Analysis and Sufficiency percentage.If this is not applicable in your case, just enter 'NA'."   value=''   />
				<?php if(isset($DASMATPercentMICA) && $DASMATPercentMICA!=""){ ?>
				  <script>
				      document.getElementById("DASMATPercentMICA").value = "<?php echo str_replace("\n", '\n', $DASMATPercentMICA );  ?>";
				      document.getElementById("DASMATPercentMICA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'DASMATPercentMICA_error'></div></div>
				</div>
				</div>
			</li>
			
			<li id="mat7" style="display: none;">
				<div class='additionalInfoLeftCol'>
				<label>Intelligence and Critical Reasoning :</label>
				<div class='fieldBoxLarge'>
				<input type='text' name='reasoningMATScoreMICA' id='reasoningMATScoreMICA'  allowNA="true"  maxlength="5" minlength="1" caption="score" validate="validateFloat"   tip="Please enter your Intelligence and Critical Reasoning Score.If this is not applicable in your case, just enter 'NA'."   value=''   />
				<?php if(isset($reasoningMATScoreMICA) && $reasoningMATScoreMICA!=""){ ?>
				  <script>
				      document.getElementById("reasoningMATScoreMICA").value = "<?php echo str_replace("\n", '\n', $reasoningMATScoreMICA );  ?>";
				      document.getElementById("reasoningMATScoreMICA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'reasoningMATScoreMICA_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>ICR Percentage: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='reasoningMATPercentileMICA' id='reasoningMATPercentileMICA' allowNA="true" maxlength="5" minlength="1" caption="percentage" validate="validateFloat"   tip="Please enter your Intelligence and Critical Reasoning percentage.If this is not applicable in your case, just enter 'NA'."   value=''   />
				<?php if(isset($reasoningMATPercentileMICA) && $reasoningMATPercentileMICA!=""){ ?>
				  <script>
				      document.getElementById("reasoningMATPercentileMICA").value = "<?php echo str_replace("\n", '\n', $reasoningMATPercentileMICA );  ?>";
				      document.getElementById("reasoningMATPercentileMICA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'reasoningMATPercentileMICA_error'></div></div>
				</div>
				</div>
			</li>
			
			<li id="mat8" style="display:none;  padding-bottom:15px">
				<div class='additionalInfoLeftCol'>
				<label>Indian and Global Environment:</label>
				<div class='fieldBoxLarge'>
				<input type='text' name='IGEMATScoreMICA' id='IGEMATScoreMICA'  allowNA="true"  maxlength="5" minlength="1" caption="score" validate="validateFloat"   tip="Please enter your Indian and Global Environment score.If this is not applicable in your case, just enter 'NA'."   value=''   />
				<?php if(isset($IGEMATScoreMICA) && $IGEMATScoreMICA!=""){ ?>
				  <script>
				      document.getElementById("IGEMATScoreMICA").value = "<?php echo str_replace("\n", '\n', $IGEMATScoreMICA );  ?>";
				      document.getElementById("IGEMATScoreMICA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'IGEMATScoreMICA_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>IGE Percentage: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='IGEMATPercentMICA' id='IGEMATPercentMICA' allowNA="true" maxlength="5" minlength="1" caption="percentage" validate="validateFloat"   tip="Please enter your Indian and Global Environment percentage.If this is not applicable in your case, just enter 'NA'."   value=''   />
				<?php if(isset($IGEMATPercentMICA) && $IGEMATPercentMICA!=""){ ?>
				  <script>
				      document.getElementById("IGEMATPercentMICA").value = "<?php echo str_replace("\n", '\n', $IGEMATPercentMICA );  ?>";
				      document.getElementById("IGEMATPercentMICA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'IGEMATPercentMICA_error'></div></div>
				</div>
				</div>
			</li>
			
			   <li id="mat2" style="display:none;" >
			<div class='additionalInfoLeftCol'>
				<label>Composite Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='matScoreAdditional' id='matScoreAdditional'  validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"        tip="Please enter your MAT February 2014 / May 2014 / September 2014 or December 2014 Score."   value=''  allowNA="true" />
				<?php if(isset($matScoreAdditional) && $matScoreAdditional!=""){ ?>
				  <script>
				      document.getElementById("matScoreAdditional").value = "<?php echo str_replace("\n", '\n', $matScoreAdditional );  ?>";
				      document.getElementById("matScoreAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'matScoreAdditional_error'></div></div>
				</div>
			</div>
			<div class="additionalInfoRightCol">
				<label>Composite Score Percentage: </label>
				<div class='float_L'>
				   <input class="textboxSmaller"  type='text' name='matPercentileAdditional' id='matPercentileAdditional'  validate="validateFloat"  allowNA="true"  caption="Percentage"   minlength="1"   maxlength="5"     tip="Mention your percentage in the exam. If you don't know your percentage, enter NA."   value=''  />
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
			
			<?php
			    if(isset($testNamesMICA) && $testNamesMICA!="" && strpos($testNamesMICA,'MAT')!==false){ ?>
			    <script>
				    checkTestScore(document.getElementById('testNamesMICA5'));
			    </script>
			<?php
			    }
			?>
			
		      <li id="cmat2" style="display: none;"><h3 class="upperCase">CMAT Score (Please enter your CMAT February / September 2014 Score)</h3>
			<div class="additionalInfoLeftCol">
				<label>CMAT Roll No: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='cmatRollNumberAdditional' id='cmatRollNumberAdditional'  validate="validateStr"  allowNA="true"  caption="Roll Number"   minlength="1"   maxlength="20"     tip="Mention your roll number for the CMAT exam. If you do not have the roll number, enter NA"   value=''  />
				<?php if(isset($cmatRollNumberAdditional) && $cmatRollNumberAdditional!=""){ ?>
						<script>
						document.getElementById("cmatRollNumberAdditional").value = "<?php echo str_replace("\n", '\n', $cmatRollNumberAdditional );  ?>";
						document.getElementById("cmatRollNumberAdditional").style.color = "";
						</script>
					  <?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'cmatRollNumberAdditional_error'></div></div>
				</div>
			</div>
			
			<div class="additionalInfoRightCol">
				<label>Rank : </label>
				<div class='float_L'>
				   <input class="textboxSmaller"  type='text' name='cmatRankAdditional' id='cmatRankAdditional'  validate="validateFloat"  allowNA="true"  caption="Rank"   minlength="1"   maxlength="10"     tip="Mention your rank in the CMAT exam."   value=''  />
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
			<li id="cmat1" style="display:none;">
			
			
				<div class='additionalInfoLeftCol'>
				<label>CMAT TEST DATE: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='cmatDateOfExaminationAdditional' id='cmatDateOfExaminationAdditional' readonly maxlength='10'     validate="validateDateForms"  caption="date"     tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('cmatDateOfExaminationAdditional'),'cmatDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />
				&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='cmatDateOfExaminationAdditional_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('cmatDateOfExaminationAdditional'),'cmatDateOfExaminationAdditional_dateImg','dd/MM/yyyy'); return false;" />
				<?php if(isset($cmatDateOfExaminationAdditional) && $cmatDateOfExaminationAdditional!=""){ ?>
				  <script>
				      document.getElementById("cmatDateOfExaminationAdditional").value = "<?php echo str_replace("\n", '\n', $cmatDateOfExaminationAdditional );  ?>";
				      document.getElementById("cmatDateOfExaminationAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'cmatDateOfExaminationAdditional_error'></div></div>
				</div>
				</div>
				
			</li>
			<li id="cmat5" style="display: none;">
				<div class="additionalInfoLeftCol">
				<label style="font-weight:normal">Quantitative Techniques and Data Interpretation : </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='quantCMATScoreMICA' id='quantCMATScoreMICA'  validate="validateFloat"  allowNA="true"  caption="score"   minlength="1"   maxlength="5"     tip="Mention your Quantitative Techniques and Data Interpretation score in the exam. If you don't know your score, enter NA"   value=''  />
				<?php if(isset($quantCMATScoreMICA) && $quantCMATScoreMICA!=""){ ?>
						<script>
						document.getElementById("quantCMATScoreMICA").value = "<?php echo str_replace("\n", '\n', $quantCMATScoreMICA );  ?>";
						document.getElementById("quantCMATScoreMICA").style.color = "";
						</script>
					  <?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'quantCMATScoreMICA_error'></div></div>
				</div>
			</div>
			
			<div class="additionalInfoRightCol">
				<label>Logical Reasoning : </label>
				<div class='float_L'>
				   <input class="textboxSmaller"  type='text' name='logicalCMATReasoningMICA' id='logicalCMATReasoningMICA'  validate="validateFloat"  allowNA="true"  caption="score"   minlength="1"   maxlength="5"     tip="Mention your Logical Reasoning Score in the exam. If you don't know your score, enter NA."   value=''  />
					<?php if(isset($logicalCMATReasoningMICA) && $logicalCMATReasoningMICA!=""){ ?>
							<script>
							document.getElementById("logicalCMATReasoningMICA").value = "<?php echo str_replace("\n", '\n', $logicalCMATReasoningMICA );  ?>";
							document.getElementById("logicalCMATReasoningMICA").style.color = "";
							</script>
						  <?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'logicalCMATReasoningMICA_error'></div></div>
				</div>
			</div>
		</li>
			<li id="cmat3" style="display: none;">
		
			<div class="additionalInfoLeftCol">
				<label style="font-weight:normal">Language Comprehension:</label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='langCMATScoreMICA' id='langCMATScoreMICA'  validate="validateFloat"  allowNA="true"  caption="score"   minlength="1"   maxlength="5"     tip="Mention your Language Comprehension Score in the exam. If you don't know your score, enter NA"   value=''  />
				<?php if(isset($langCMATScoreMICA) && $langCMATScoreMICA!=""){ ?>
						<script>
						document.getElementById("langCMATScoreMICA").value = "<?php echo str_replace("\n", '\n', $langCMATScoreMICA );  ?>";
						document.getElementById("langCMATScoreMICA").style.color = "";
						</script>
					  <?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'langCMATScoreMICA_error'></div></div>
				</div>
			</div>
			
			<div class="additionalInfoRightCol">
				<label>General Awareness: </label>
				<div class='float_L'>
				   <input class="textboxSmaller"  type='text' name='generalCMATScoreMICA' id='generalCMATScoreMICA'  validate="validateFloat"  allowNA="true"  caption="score"   minlength="1"   maxlength="5"     tip="Mention your General Awareness Score in the exam. If you don't know your score, enter NA."   value=''  />
					<?php if(isset($generalCMATScoreMICA) && $generalCMATScoreMICA!=""){ ?>
							<script>
							document.getElementById("generalCMATScoreMICA").value = "<?php echo str_replace("\n", '\n', $generalCMATScoreMICA );  ?>";
							document.getElementById("generalCMATScoreMICA").style.color = "";
							</script>
						  <?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'generalCMATScoreMICA_error'></div></div>
				</div>
			</div>
			
			</li>
			
			<li id="cmat4" style="display: none;  padding-bottom:15px">
			
			<div class='additionalInfoLeftCol'>
				<label>Total Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='cmatScoreAdditional' id='cmatScoreAdditional'  validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"        tip="(Please enter your CMAT February / September 2014 Score)."   value=''  allowNA="true" />
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
			
		
				<?php
			    if(isset($testNamesMICA) && $testNamesMICA!="" && strpos($testNamesMICA,'CMAT')!==false){ ?>
			    <script>
				    checkTestScore(document.getElementById('testNamesMICA4'));
			    </script>
			<?php
			    }
			?>
			
			
			
		
		
		
		<li id="atma1" style="display:none;">
			<h3 class="upperCase">ATMA Score (Please enter your ATMA February 2014 / May 2014 or July 2014 Score)</h3>
			<div class="additionalInfoLeftCol">
				<label style="font-weight:normal">ATMA Roll No.: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='atmaRollNumberAdditional' id='atmaRollNumberAdditional'  validate="validateStr"  allowNA="true"  caption="Roll Number"   minlength="1"   maxlength="20"     tip="Mention your roll number for the ATMA exam. If you do not have the roll number, enter NA"   value=''  />
				<?php if(isset($atmaRollNumberAdditional) && $atmaRollNumberAdditional!=""){ ?>
						<script>
						document.getElementById("atmaRollNumberAdditional").value = "<?php echo str_replace("\n", '\n', $atmaRollNumberAdditional );  ?>";
						document.getElementById("atmaRollNumberAdditional").style.color = "";
						</script>
					  <?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'atmaRollNumberAdditional_error'></div></div>
				</div>
			</div>
			
				<div class='additionalInfoRightCol'>
				<label>ATMA Test Date: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='atmaDateOfExaminationAdditional' id='atmaDateOfExaminationAdditional' readonly maxlength='10'     validate="validateDateForms"  caption="date"     tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('atmaDateOfExaminationAdditional'),'atmaDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />
				&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='atmaDateOfExaminationAdditional_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('atmaDateOfExaminationAdditional'),'atmaDateOfExaminationAdditional_dateImg','dd/MM/yyyy'); return false;" />
				<?php if(isset($atmaDateOfExaminationAdditional) && $atmaDateOfExaminationAdditional!=""){ ?>
				  <script>
				      document.getElementById("atmaDateOfExaminationAdditional").value = "<?php echo str_replace("\n", '\n', $atmaDateOfExaminationAdditional );  ?>";
				      document.getElementById("atmaDateOfExaminationAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'atmaDateOfExaminationAdditional_error'></div></div>
				</div>
				</div>

				<!----div class='additionalInfoRightCol'>
				<label>GMAT Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gmatScoreAdditional' id='gmatScoreAdditional'  validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"        tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter NA."   value=''  allowNA="true" />
				<?php // if(isset($gmatScoreAdditional) && $gmatScoreAdditional!=""){ ?>
				  <script>
				      document.getElementById("gmatScoreAdditional").value = "<?php //echo str_replace("\n", '\n', $gmatScoreAdditional );  ?>";
				      document.getElementById("gmatScoreAdditional").style.color = "";
				  </script>
				<?php // } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gmatScoreAdditional_error'></div></div>
				</div>
				</div>-->
				
			</li>
			
		    
			<li id="atma3" style="display: none;">
			<div class="additionalInfoLeftCol">
				<label style="font-weight:normal">ATMA Quantitative : </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='quantATMAcoreMICA' id='quantATMAcoreMICA'  validate="validateFloat"  allowNA="true"  caption="score"   minlength="1"   maxlength="5"     tip="Mention your quantitative score in the exam. If you don't know your score, enter NA"   value=''  />
				<?php if(isset($quantATMAcoreMICA) && $quantATMAcoreMICA!=""){ ?>
						<script>
						document.getElementById("quantATMAcoreMICA").value = "<?php echo str_replace("\n", '\n', $quantATMAcoreMICA );  ?>";
						document.getElementById("quantATMAcoreMICA").style.color = "";
						</script>
					  <?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'quantATMAcoreMICA_error'></div></div>
				</div>
			</div>
			
			<div class="additionalInfoRightCol">
				<label>ATMA Quantitative Percentile: </label>
				<div class='float_L'>
				   <input class="textboxSmaller"  type='text' name='quantATMAPercentMICA' id='quantATMAPercentMICA'  validate="validateFloat"  allowNA="true"  caption="Percentile"   minlength="1"   maxlength="5"     tip="Mention your quantitative percentile in the exam. If you don't know your percentile, enter NA."   value=''  />
					<?php if(isset($quantATMAPercentMICA) && $quantATMAPercentMICA!=""){ ?>
							<script>
							document.getElementById("quantATMAPercentMICA").value = "<?php echo str_replace("\n", '\n', $quantATMAPercentMICA );  ?>";
							document.getElementById("quantATMAPercentMICA").style.color = "";
							</script>
						  <?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'quantATMAPercentMICA_error'></div></div>
				</div>
			</div>
			
			</li>
			
			<li id="atma4" style="display: none;">
			<div class="additionalInfoLeftCol">
				<label style="font-weight:normal">ATMA Verbal : </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='verbalATMAScoreMICA' id='verbalATMAScoreMICA'  validate="validateFloat"  allowNA="true"  caption="score"   minlength="1"   maxlength="5"     tip="Mention your verbal score in the exam. If you don't know your score, enter NA"   value=''  />
				<?php if(isset($verbalATMAScoreMICA) && $verbalATMAScoreMICA!=""){ ?>
						<script>
						document.getElementById("verbalATMAScoreMICA").value = "<?php echo str_replace("\n", '\n', $verbalATMAScoreMICA );  ?>";
						document.getElementById("verbalATMAScoreMICA").style.color = "";
						</script>
					  <?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'verbalATMAScoreMICA_error'></div></div>
				</div>
			</div>
			
			<div class="additionalInfoRightCol">
				<label>ATMA Verbal Percentile: </label>
				<div class='float_L'>
				   <input class="textboxSmaller"  type='text' name='verbalATMAPercentMICA' id='verbalATMAPercentMICA'  validate="validateFloat"  allowNA="true"  caption="Percentile"   minlength="1"   maxlength="5"     tip="Mention your verbal percentile in the exam. If you don't know your percentile, enter NA."   value=''  />
					<?php if(isset($verbalATMAPercentMICA) && $verbalATMAPercentMICA!=""){ ?>
							<script>
							document.getElementById("verbalATMAPercentMICA").value = "<?php echo str_replace("\n", '\n', $verbalATMAPercentMICA );  ?>";
							document.getElementById("verbalATMAPercentMICA").style.color = "";
							</script>
						  <?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'verbalATMAPercentMICA_error'></div></div>
				</div>
			</div>
			
			</li>
			
			<li id="atma5" style="display: none;  padding-bottom:15px">
			<div class="additionalInfoLeftCol">
				<label style="font-weight:normal">ATMA Analytical: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='analyticalATMAScoreMICA' id='analyticalATMAScoreMICA'  validate="validateFloat"  allowNA="true"  caption="score"   minlength="1"   maxlength="5"     tip="Mention your analyitcal score in the exam. If you don't know your score, enter NA"   value=''  />
				<?php if(isset($analyticalATMAScoreMICA) && $analyticalATMAScoreMICA!=""){ ?>
						<script>
						document.getElementById("analyticalATMAScoreMICA").value = "<?php echo str_replace("\n", '\n', $analyticalATMAScoreMICA );  ?>";
						document.getElementById("analyticalATMAScoreMICA").style.color = "";
						</script>
					  <?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'analyticalATMAScoreMICA_error'></div></div>
				</div>
			</div>
			
			<div class="additionalInfoRightCol">
				<label>ATMA Analytical Percentile: </label>
				<div class='float_L'>
				   <input class="textboxSmaller"  type='text' name='analyticalATMAPercentMICA' id='analyticalATMAPercentMICA'  validate="validateFloat"  allowNA="true"  caption="Percentile"   minlength="1"   maxlength="5"     tip="Mention your analytical percentile in the exam. If you don't know your percentile, enter NA."   value=''  />
					<?php if(isset($analyticalATMAPercentMICA) && $analyticalATMAPercentMICA!=""){ ?>
							<script>
							document.getElementById("analyticalATMAPercentMICA").value = "<?php echo str_replace("\n", '\n', $analyticalATMAPercentMICA );  ?>";
							document.getElementById("analyticalATMAPercentMICA").style.color = "";
							</script>
						  <?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'analyticalATMAPercentMICA_error'></div></div>
				</div>
			</div>
			
			</li>
			  <li id="atma2" style="display: none;">
			<div class='additionalInfoLeftCol'>
				<label>ATMA Total Scaled Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='atmaScoreAdditional' id='atmaScoreAdditional'  validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"        tip="Please enter your ATMA February 2014 / May 2014 or July 2014 Score."   value=''  allowNA="true" />
				<?php if(isset($atmaScoreAdditional) && $atmaScoreAdditional!=""){ ?>
				  <script>
				      document.getElementById("atmaScoreAdditional").value = "<?php echo str_replace("\n", '\n', $atmaScoreAdditional );  ?>";
				      document.getElementById("atmaScoreAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'atmaScoreAdditional_error'></div></div>
				</div>
			</div>
			<div class="additionalInfoRightCol">
				<label>ATMA Total Percentile: </label>
				<div class='float_L'>
				   <input class="textboxSmaller"  type='text' name='atmaPercentileAdditional' id='atmaPercentileAdditional'  validate="validateFloat"  allowNA="true"  caption="Percentile"   minlength="1"   maxlength="5"     tip="Mention your percentile in the atma exam. If you don't know your percentile, enter NA."   value=''  />
					<?php if(isset($atmaPercentileAdditional) && $atmaPercentileAdditional!=""){ ?>
							<script>
							document.getElementById("atmaPercentileAdditional").value = "<?php echo str_replace("\n", '\n', $atmaPercentileAdditional );  ?>";
							document.getElementById("atmaPercentileAdditional").style.color = "";
							</script>
						  <?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'atmaPercentileAdditional_error'></div></div>
				</div>
			</div>
			
			</li>
			
			
				<?php
			    if(isset($testNamesMICA) && $testNamesMICA!="" && strpos($testNamesMICA,'ATMA')!==false){ ?>
			    <script>
				    checkTestScore(document.getElementById('testNamesMICA3'));
			    </script>
			<?php
			    }
			?>
			
			
		<?php $this->load->view('Templates/educationAddressInfoMICA');?>
		
			<?php if($action != 'updateScore'):?>
			<?php
			// Find out graduation course name, if available
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
			}
			
		    ?>
			<li>
				<div class='additionalInfoLeftCol'>
				<label><?php echo $graduationCourseName;?> specialization: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gradCourseSpecializationMICA' id='gradCourseSpecializationMICA'   required="true"     validate="validateStr"  caption="subjects"   minlength="1"   maxlength="100"   tip="Enter the sepcialzation for your graduation here for example mechanical engineering, economics, commerce etc."   value=''   />
				<?php if(isset($gradCourseSpecializationMICA) && $gradCourseSpecializationMICA!=""){ ?>
				  <script>
				      document.getElementById("gradCourseSpecializationMICA").value = "<?php echo str_replace("\n", '\n', $gradCourseSpecializationMICA );  ?>";
				      document.getElementById("gradCourseSpecializationMICA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradCourseSpecializationMICA_error'></div></div>
				</div>
				</div>
				
				
			</li>
			<?php
			$actualPostGradArray = array();
			foreach($otherCourses as $otherCourseId => $otherCourseName) {
				$actualPostGradArray[] = $otherCourseId;
			}
			
			if(!in_array('1',$actualPostGradArray)){
			?>	<span style='display: none;'>
				<input type='text' name='otherCourseSpecializationMICA_mul_1' />
				<input type='radio' name='pgDegreeMICA_mul_1' value='No' checked/>
				</span>
			<?php
			}
			if(!in_array('2',$actualPostGradArray)){
			?>	<span style='display: none;'>
				<input type='text' name='otherCourseSpecializationMICA_mul_2' />
				<input type='radio' name='pgDegreeMICA_mul_2' value='No' checked/>
				</span>
			<?php
			}
			if(!in_array('3',$actualPostGradArray)){
			?>
				<span style='display: none;'>
				<input type='text' name='otherCourseSpecializationMICA_mul_3' />
				<input type='radio' name='pgDegreeMICA_mul_3' value='No' checked/>
				</span>
			<?php
			}
			if(!in_array('4',$actualPostGradArray)){
			?>
				<span style='display: none;'>
				<input type='text' name='otherCourseSpecializationMICA_mul_4' />
				<input type='radio' name='pgDegreeMICA_mul_4' value='No' checked/>
				</span>
			<?php
			}
			$i=0;
			if(count($otherCourses)>0) { 
				foreach($otherCourses as $otherCourseId => $otherCourseName) {
					$collegeSpecialization = 'otherCourseSpecializationMICA_mul_'.$otherCourseId;
					$collegeSpecializationVal = $$collegeSpecialization;
					$pgDegreeCheck = 'pgDegreeMICA_mul_'.$otherCourseId;
					$pgDegreeCheckVal = $$pgDegreeCheck;
					$i++;
			?>
			<li>
				<div class='additionalInfoLeftCol'>
				<label><?php echo $otherCourseName;?> specialization: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='<?php echo $collegeSpecialization;?>' id='<?php echo $collegeSpecialization;?>'   required="true"     validate="validateStr"  caption="subjects"   minlength="1"   maxlength="100"   tip="Enter the specialzation for your <?php echo $otherCourseName;?> here for example mechanical engineering, economics, commerce etc."   value=''   />
				<?php if(isset($collegeSpecializationVal) && $collegeSpecializationVal!=""){ ?>
				<script>
				      document.getElementById("<?php echo $collegeSpecialization;?>").value = "<?php echo str_replace("\n", '\n', $collegeSpecializationVal );  ?>";
				      document.getElementById("<?php echo $collegeSpecialization;?>").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= '<?php echo $collegeSpecialization;?>_error'></div></div>
				</div>
				</div>
				
				<div class='additionalInfoRightCol'>
				<label>Is this a PG Degree? </label>
				<div class='fieldBoxLarge'>
				<input type='radio' name='<?php echo $pgDegreeCheck;?>' id='pgDegreeYes_<?php echo $i;?>'   value='Yes'  checked   onmouseover="showTipOnline('Please select Yes If you have PG Degree',this);" onmouseout="hidetip();"></input><span  onmouseover="showTipOnline('Please select Yes If you have PG Degree',this);" onmouseout="hidetip();" >Yes</span>&nbsp;&nbsp;
				<input type='radio' name='<?php echo $pgDegreeCheck;?>' id='pgDegreeNo_<?php echo $i;?>'   value='No'  checked   onmouseover="showTipOnline('Please select No If you have PG Degree',this);" onmouseout="hidetip();"></input><span  onmouseover="showTipOnline('Please select No If you have PG Degree',this);" onmouseout="hidetip();" >No</span>&nbsp;&nbsp;
				<?php if(isset($pgDegreeCheckVal) && $pgDegreeCheckVal!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements['<?php echo $pgDegreeCheck;?>'];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $pgDegreeCheckVal;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				</div>
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
					}
				}
			}
			$actualMulArray = array();
			foreach($workCompanies as $workCompanyKey => $workCompany) {
				$actualMulArray[] = $workCompanyKey;
			}
			
			if(!in_array('_mul_0',$actualMulArray)){
			?>	<span style='display: none;'>
				<input type='text' name='annualSalaryMICA' />
				<input type='text' name='experienceMICA' />
				<input type='radio' name='experienceCheckMICA' value='No' checked/>
				</span>
			<?php
			}
			if(!in_array('_mul_1',$actualMulArray)){
			?>	<span style='display: none;'>
				<input type='text' name='annualSalaryMICA_mul_1' />
				<input type='text' name='experienceMICA_mul_1' />
				<input type='radio' name='experienceCheckMICA_mul_1' value='No' checked/>
				</span>
			<?php
			}
			if(!in_array('_mul_2',$actualMulArray)){
			?>
				<span style='display: none;'>
				<input type='text' name='annualSalaryMICA_mul_2' />
				<input type='text' name='experienceMICA_mul_2' />
				<input type='radio' name='experienceCheckMICA_mul_2' value='No' checked/>
				</span>
			<?php
			}
			
			if(count($workCompanies) > 0) {
				$j = 0;
				$counter = 1;
				foreach($workCompanies as $workCompanyKey => $workCompany) {
					$salaryFieldName = 'annualSalaryMICA'.($workCompanyKey=='_mul_0'?'':$workCompanyKey);
					$salaryValue = $$salaryFieldName;
					$experienceFieldName = 'experienceMICA'.($workCompanyKey=='_mul_0'?'':$workCompanyKey);
					$experienceValue = $$experienceFieldName;
					$experienceFieldCheck = 'experienceCheckMICA'.($workCompanyKey=='_mul_0'?'':$workCompanyKey);
					$experienceFieldCheckValue = $$experienceFieldCheck;
					$experienceIncome = 'experienceIncomeMICA'.($workCompanyKey=='_mul_0'?'':$workCompanyKey);
			?>
			<li>
				<div class='additionalInfoLeftCol'>
				<label>Was the job at <?=$workCompany;?>  a full time job?: </label>
				<div class='fieldBoxLarge'>
				<input type='radio' name='<?php echo $experienceFieldCheck;?>' id='<?php echo $experienceFieldCheck;?>_0'   <?php if(!isset($experienceFieldCheckValue) && $experienceFieldCheckValue=="" && isset($workCompany) && $workCompany!="" ){?> checked <?php } ?> value='Yes' onmouseover="showTipOnline('Please select Yes If you have work experience',this);" onmouseout="hidetip();" onclick="changeJobStatus('Yes','<?php echo $salaryFieldName;?>','<?php echo $experienceFieldName;?>','<?php echo $experienceIncome;?>');"></input><span  onmouseover="showTipOnline('Please select Yes If you have work experience',this);" onmouseout="hidetip();" >Yes</span>&nbsp;&nbsp;
				<input type='radio' name='<?php echo $experienceFieldCheck;?>' id='<?php echo $experienceFieldCheck;?>_1'   value='No'  onmouseover="showTipOnline('Please select Yes If you have work experience',this);" onmouseout="hidetip();" onclick="changeJobStatus('No','<?php echo $salaryFieldName;?>','<?php echo $experienceFieldName;?>','<?php echo $experienceIncome;?>');"></input><span  onmouseover="showTipOnline('Please select No If you have work experience',this);" onmouseout="hidetip();" >No</span>&nbsp;&nbsp;
				<?php if(isset($experienceFieldCheckValue) && $experienceFieldCheckValue!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["<?php echo $experienceFieldCheck;?>"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $experienceFieldCheckValue;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= '<?php echo $experienceFieldCheck;?>_error'></div></div>
				</div>
				</div>

			</li>
			<li id="<?php echo $experienceIncome;?>" <?php if($experienceFieldCheckValue=='No'){?> style="display: none;" <?php } ?>>
				<div class='additionalInfoLeftCol'>
				<label><?=$workCompany;?> Income: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='<?php echo $salaryFieldName;?>' id='<?php echo $salaryFieldName;?>'   validate="validateInteger"  allowNA="true"  minlength="1"   maxlength="10"   caption="income"   minlength="1"   maxlength="100"   tip="Enter total income per month in <?=$workCompany;?>.If this is not applicable in your case, just enter 'NA'."   value=''   <?php if(!isset($experienceFieldCheckValue) && $experienceFieldCheckValue=="" && isset($workCompany) && $workCompany!="" ){?> required='true' <?php } ?>/>
				<?php if(isset($salaryValue) && $salaryValue!=""){ ?>
				<script>
				      document.getElementById("<?php echo $salaryFieldName;?>").value = "<?php echo str_replace("\n", '\n', $salaryValue );  ?>";
				      document.getElementById("<?php echo $salaryFieldName;?>").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= '<?php echo $salaryFieldName;?>_error'></div></div>
				</div>
				</div>
				
				<div class='additionalInfoRightCol'>
				<label>Experience in Months: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='<?php echo $experienceFieldName;?>' id='<?php echo $experienceFieldName;?>'  validate="validateInteger"  allowNA="true"  minlength="1"   maxlength="10"   caption="experience"   minlength="1"   maxlength="100"   tip="Enter Experience in Months.If this is not applicable in your case, just enter 'NA'."   value=''  <?php if(!isset($experienceFieldCheckValue) && $experienceFieldCheckValue=="" && isset($workCompany) && $workCompany!="" ){?> required='true' <?php } ?> />
				<?php if(isset($experienceValue) && $experienceValue!=""){ ?>
				<script>
				      document.getElementById("<?php echo $experienceFieldName;?>").value = "<?php echo str_replace("\n", '\n', $experienceValue );  ?>";
				      document.getElementById("<?php echo $experienceFieldName;?>").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= '<?php echo $experienceFieldName;?>_error'></div></div>
				</div>
				</div>
			</li>
			<?php
				}
			}
			$sourceMICAArray = explode(',',$sourceMICA);
			?>
			<li>
				<div class='additionalInfoLeftCol'>
				<h3 class="upperCase">Other Details</h3>
				<label>How did you come to know about MICA? (Press and hold Ctrl key for multiple select): </label>
				<div class='fieldBoxLarge'>
				<select name='sourceMICA[]' id='sourceMICA'   onChange="showOtherMultiSelectOption(this,'otherSourceMICA','otherSourceMICALI');" validate="multipleSelect" required="true"  caption="atleast one option" tip=""  MULTIPLE SIZE=5><option value='Advertisements' selected>Advertisements</option><option value='Friends/Parents/Relatives' >Friends/Parents/Relatives</option><option value='Industry Professionals' >Industry Professionals</option><option value='MICA Website' >MICA Website</option><option value='MICA Alumni' >MICA Alumni</option><option value='Notice at college' >Notice at college</option><option value='Notice at Coaching Classes' >Notice at Coaching Classes</option><option value='Others' >Others</option></select>
				<?php if(isset($sourceMICA) && $sourceMICA!=""){ ?>
			    <script>
				var select = document.getElementById("sourceMICA"); 
				var optionsToSelect = Array();
				    <?php $arr = explode(",",$sourceMICA); 
				      for($i=0;$i<count($arr);$i++){ ?>
					  optionsToSelect[<?php echo $i;?>] = "<?php echo $arr[$i];?>";
				    <?php
				      }
				    ?>
				    for ( var i = 0, l = select.options.length, o; i < l; i++ )
				    {
				      o = select.options[i];
				      o.selected = false;
				      if ( optionsToSelect.indexOf( o.text ) != -1 )
				      {
					o.selected = true;
				      }
				    }
			    </script>
			  <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'sourceMICA_error'></div></div>
				</div>
				</div>
			</li>

			<li id="otherSourceMICALI" <?php if(empty($otherSourceMICA) || !in_array('Others',$sourceMICAArray)){?> style="display:none;" <?php } ?>>
				<div class='additionalInfoLeftCol'>
				<label>How did you come to know about MICA?: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='otherSourceMICA' id='otherSourceMICA'  validate="validateStr" maxlength="50" minlength="2" caption="source name" tip="Specify how did you come to know about MICA"   value=''   />
				<?php if(isset($otherSourceMICA) && $otherSourceMICA!=""){ ?>
				  <script>
				      document.getElementById("otherSourceMICA").value = "<?php echo str_replace("\n", '\n', $otherSourceMICA );  ?>";
				      document.getElementById("otherSourceMICA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'otherSourceMICA_error'></div></div>
				</div>
				</div>
			</li>
			<?php $sourcePublicationMICAArray = explode(',',$sourcePublicationMICA);?>
			<li>
				<div class='additionalInfoLeftCol' style="width:100%">
				<label>In what publication did you read the MICA admissions announcement? (Press and hold Ctrl key for multiple select): </label>
				<div class='fieldBoxLarge'>
				<select name='sourcePublicationMICA[]' id='sourcePublicationMICA'    onChange="showOtherMultiSelectOption(this,'otherPublicationSourceMICA','otherPublicationSourceMICALI');"  validate="multipleSelect" required="true"  caption="atleast one option"  MULTIPLE SIZE=5><option value='Advance`edge MBA (IMS)' selected>Advance`edge MBA (IMS)</option><option value='Hindustan Times' >Hindustan Times</option><option value='INDIA TODAY (Aspire)' >INDIA TODAY (Aspire)</option><option value='Management Compass (CL)' >Management Compass (CL)</option><option value='MBA Education and Careers (T.I.M.E.)' >MBA Education and Careers (T.I.M.E.)</option><option value='Telegraph' >Telegraph</option><option value='The Hindu' >The Hindu</option><option value='Times of India' >Times of India</option><option value='Business Line' >Business Line</option><option value='PagalGuy' >PagalGuy</option><option value='MBA Universe' >MBA Universe</option><option value='Mint' >Mint</option><option value='Others' >Others</option></select>
				<?php if(isset($sourcePublicationMICA) && $sourcePublicationMICA!=""){ ?>
			    <script>
				var select = document.getElementById("sourcePublicationMICA"); 
				var optionsToSelect = Array();
				    <?php $arr = explode(",",$sourcePublicationMICA); 
				      for($i=0;$i<count($arr);$i++){ ?>
					  optionsToSelect[<?php echo $i;?>] = "<?php echo $arr[$i];?>";
				    <?php
				      }
				    ?>
				    for ( var i = 0, l = select.options.length, o; i < l; i++ )
				    {
				      o = select.options[i];
				      o.selected = false;
				      if ( optionsToSelect.indexOf( o.text ) != -1 )
				      {
					o.selected = true;
				      }
				    }
			    </script>
			  <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'sourcePublicationMICA_error'></div></div>
				</div>
				</div>
			</li>

			<li id="otherPublicationSourceMICALI" <?php if(empty($otherSourceMICA) || !in_array('Others',$sourcePublicationMICAArray)){?> style="display:none;" <?php } ?>>
				<div class='additionalInfoLeftCol'>
				<label>In what publication did you read the MICA admissions announcement?: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='otherPublicationSourceMICA' id='otherPublicationSourceMICA'    validate="validateStr" maxlength="50" minlength="2" caption="publication"      value='' tip="Specify the publication name"  />
				<?php if(isset($otherPublicationSourceMICA) && $otherPublicationSourceMICA!=""){ ?>
				  <script>
				      document.getElementById("otherPublicationSourceMICA").value = "<?php echo str_replace("\n", '\n', $otherPublicationSourceMICA );  ?>";
				      document.getElementById("otherPublicationSourceMICA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'otherPublicationSourceMICA_error'></div></div>
				</div>
				</div>
			</li>
			<?php if($action != 'updateScore'):?>			
			<?php if(is_array($gdpiLocations)): ?>
			<li>
				<label style="font-weight:normal">Preferred Test City: </label>
				<div class='fieldBoxLarge'>
				<select name='preferredGDPILocation' id='preferredGDPILocation' onmouseover="showTipOnline('Select your preferred GD/PI location.',this);" onmouseout='hidetip();'  validate="validateSelect"  minlength="1"   maxlength="1500"  required="true" caption="Preferred GD/PI location">
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
				I, <?php echo $nameOfTheUser; ?>, certify that the information furnished in this application is true to the best of my knowledge. My application may be rejected and admission shall be cancelled if any information provided herein is found to be incorrect at any time during or after admission.Detailed Terms and Conditions can be found <a href="/public/onlineforms_TnC/mica/tnc.doc" target="_blank">Here.</a>
				</div>
				<div class="spacer10 clearFix"></div>
				<div class='additionalInfoLeftCol'>
				<label>I agree to the terms stated above: </label>
				<div class='fieldBoxLarge'>
				<input type='checkbox' name='agreeToTermsMICA' id='agreeToTermsMICA'  value='1'  required="true" caption="Please agree to the terms stated above" validate="validateChecked"></input><span ><span>&nbsp;&nbsp;
				<?php if(isset($agreeToTermsMICA) && $agreeToTermsMICA!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["agreeToTermsMICA"];
				    var countCheckBoxes = 1;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes.checked = false;
					      <?php $arr = explode(",",$agreeToTermsMICA);
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
				
				<div style='display:none'><div class='errorMsg' id= 'agreeToTermsMICA_error'></div></div>
				</div>
				</div>
			</li>
			<?php endif; ?>
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
  <?php
if(empty($legalGuardianMICA)){
?>
<script>$('qualificationGuardianMICA').setAttribute('required','true');</script>
<?php
}else if($legalGuardianMICA=='Father'){
?>
<script>$('qualificationGuardianMICA').setAttribute('required','true');</script>
<?php
}else if($legalGuardianMICA=='Mother'){
?>
<script>$('qualificationGuardianMICA').setAttribute('required','true');</script>
<?php
}else if($legalGuardianMICA=='Other'){
?>
<script>$('qualificationGuardianMICA').setAttribute('required','true');</script>
<script>$('occupationGuardianMICA').setAttribute('required','true');</script>
<script>$('qualificationGuardianMICA').setAttribute('required','true');</script>
<?php
}
?>
