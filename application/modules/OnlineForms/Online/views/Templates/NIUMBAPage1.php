<style>
.cutom-tr {height: 50px;}
</style>
<script>
function changeAilmentDetails(obj){
	if(obj.value=='Yes'){	//Enable Details
		$("NIUMBA_AilmentDetails").disabled = false;
		$("NIUMBA_AilmentDetails").setAttribute('required','true');
		$("NIUMBA_AilmentDetails").value = "";
		$("NIUMBA_AilmentDetails").style.color = "";
	}
	else{
		$("NIUMBA_AilmentDetails").disabled = true;
		$("NIUMBA_AilmentDetails").removeAttribute('required');
		$("NIUMBA_AilmentDetails").value = "";
	}
	$('NIUMBA_AilmentDetails_error').innerHTML = '';
	$('NIUMBA_AilmentDetails_error').parentNode.style.display = 'none';	
}

function changeProbationDetails(obj){
	if(obj.value=='Yes'){	//Enable Details
		$("NIUMBA_AcademicProbationDetails").disabled = false;
		$("NIUMBA_AcademicProbationDetails").setAttribute('required','true');
		$("NIUMBA_AcademicProbationDetails").value = "";
		$("NIUMBA_AcademicProbationDetails").style.color = "";
	}
	else{
		$("NIUMBA_AcademicProbationDetails").disabled = true;
		$("NIUMBA_AcademicProbationDetails").removeAttribute('required');
		$("NIUMBA_AcademicProbationDetails").value = "";
	}
	$('NIUMBA_AcademicProbationDetails_error').innerHTML = '';
	$('NIUMBA_AcademicProbationDetails_error').parentNode.style.display = 'none';	
}

function checkPassportDetails(){
	$('passportDetails').style.display='';
	$("NIUMBA_passportNumber").setAttribute('required','true');
	$("NIUMBA_passportIssueDate").setAttribute('required','true');
	$("NIUMBA_passportTillDate").setAttribute('required','true');
	$("NIUMBA_issuingCountry").setAttribute('required','true');
	$("NIUMBA_visaType").setAttribute('required','true');
	$("NIUMBA_timePeriodYear").setAttribute('required','true');
	$("NIUMBA_timePeriodMonth").setAttribute('required','true');
	$("NIUMBA_ExamTaken0").setAttribute('validate','validateCheckedGroup');
	$("NIUMBA_ExamTaken1").setAttribute('validate','validateCheckedGroup');
	$("NIUMBA_ExamTaken2").setAttribute('validate','validateCheckedGroup');
}

function checkExamTaken(obj){
	var examValue = obj.value;
	if($(examValue+'Details').style.display == ''){
		$(examValue+'Details').style.display = 'none';
		if(examValue=='TOEFL'){
			$("NIUMBA_ExamRollNumber").removeAttribute('required');
			$("NIUMBA_ExamScore").removeAttribute('required');
			$('NIUMBA_ExamRollNumber_error').innerHTML = '';
			$('NIUMBA_ExamRollNumber_error').parentNode.style.display = 'none';	
			$('NIUMBA_ExamScore_error').innerHTML = '';
			$('NIUMBA_ExamScore_error').parentNode.style.display = 'none';	
			$("NIUMBA_ExamRollNumber").value = '';
			$("NIUMBA_ExamScore").value = '';
		}
		else{
			$("NIUMBA_ExamRollNumber"+examValue).removeAttribute('required');
			$("NIUMBA_ExamScore"+examValue).removeAttribute('required');			
			$('NIUMBA_ExamRollNumber'+examValue+'_error').innerHTML = '';
			$('NIUMBA_ExamRollNumber'+examValue+'_error').parentNode.style.display = 'none';	
			$('NIUMBA_ExamScore'+examValue+'_error').innerHTML = '';
			$('NIUMBA_ExamScore'+examValue+'_error').parentNode.style.display = 'none';	
			$("NIUMBA_ExamRollNumber"+examValue).value = '';
			$("NIUMBA_ExamScore"+examValue).value = '';
		}		
	}
	else{
		$(examValue+'Details').style.display = '';
		if(examValue=='TOEFL'){
			$("NIUMBA_ExamRollNumber").setAttribute('required','true');
			$('NIUMBA_ExamRollNumber_error').innerHTML = '';
			$('NIUMBA_ExamRollNumber_error').parentNode.style.display = 'none';	
			$("NIUMBA_ExamScore").setAttribute('required','true');
			$('NIUMBA_ExamScore_error').innerHTML = '';
			$('NIUMBA_ExamScore_error').parentNode.style.display = 'none';	
		}
		else{
			$("NIUMBA_ExamRollNumber"+examValue).setAttribute('required','true');
			$("NIUMBA_ExamScore"+examValue).setAttribute('required','true');			
			$('NIUMBA_ExamRollNumber'+examValue+'_error').innerHTML = '';
			$('NIUMBA_ExamRollNumber'+examValue+'_error').parentNode.style.display = 'none';	
			$('NIUMBA_ExamScore'+examValue+'_error').innerHTML = '';
			$('NIUMBA_ExamScore'+examValue+'_error').parentNode.style.display = 'none';	
		}		
	}
}
</script>
<div class='formChildWrapper'>
	<div class='formSection'>
		<ul>

<?php if($action != 'updateScore'):?>
	
	
			<li>
				<h3 class="upperCase">Additional Personal Information</h3>

				<div class='additionalInfoLeftCol' style='width:620px;'>

					<label>Height Ft.: </label>
					
					<div class='fieldBoxLarge' style="width:300px;">
						<input style='width:50px;' type='text' name='NIUMBA_HeightFeet' id='NIUMBA_HeightFeet'  validate="validateInteger"   required="true"   caption="height in feet"   minlength="1"   maxlength="1"     tip="Enter your height in feet and inches. For example, if your height is 5 feel 6 inches, enter 5 in the box against ft. and 6 in the box against in."   title="Height"  value=''   />
						<?php if(isset($NIUMBA_HeightFeet) && $NIUMBA_HeightFeet!=""){ ?>
						  <script>
						      document.getElementById("NIUMBA_HeightFeet").value = "<?php echo str_replace("\n", '\n', $NIUMBA_HeightFeet );  ?>";
						      document.getElementById("NIUMBA_HeightFeet").style.color = "";
						  </script>
						<?php } ?>
		
						&nbsp;in.:
						<input style='width:50px;' type='text' name='NIUMBA_HeightInch' id='NIUMBA_HeightInch'  validate="validateInteger"   required="true"   caption="height in inches"   minlength="1"   maxlength="2"     tip="Enter your height in feet and inches. For example, if your height is 5 feel 6 inches, enter 5 in the box against ft. and 6 in the box against in."   title="Height"  value=''   />
						<?php if(isset($NIUMBA_HeightInch) && $NIUMBA_HeightInch!=""){ ?>
						  <script>
						      document.getElementById("NIUMBA_HeightInch").value = "<?php echo str_replace("\n", '\n', $NIUMBA_HeightInch );  ?>";
						      document.getElementById("NIUMBA_HeightInch").style.color = "";
						  </script>
						<?php } ?>
						<div style='display:none;'><div class='errorMsg' id= 'NIUMBA_HeightFeet_error'></div></div>
						<div style='display:none;'><div class='errorMsg' id= 'NIUMBA_HeightInch_error'></div></div>
					</div>
				</div>
				

			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Category: </label>
				<div class='fieldBoxLarge'>
				<select style='width:150px;' name='NIUMBA_Category' id='NIUMBA_Category'    tip="Please select the appropriate category that applies to you."   title="Category"    required="true"  validate='validateSelect' caption='category' minlength='1' maxlength='50' onmouseover="showTipOnline('Please select the appropriate category that applies to you.',this);" onmouseout="hidetip();" >
				<option value='' selected>Select</option><option value='GEN' >Gen</option><option value='SC' >SC</option><option value='ST' >ST</option><option value='OBC' >OBC</option></select>
				<?php if(isset($NIUMBA_Category) && $NIUMBA_Category!=""){ ?>
			      <script>
				  var selObj = document.getElementById("NIUMBA_Category"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $NIUMBA_Category;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIUMBA_Category_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol' style="width: 800px;">
				<label>Admission category: </label>
				<div class='fieldBoxLarge' style="width:420px;">
				<input type='radio'   required="true"   name='NIUMBA_AdmissionCategory' id='NIUMBA_AdmissionCategory0'   value='NON-SPONSORED'  checked  title="Admission category"   onmouseover="showTipOnline('Please select a category under which you would want to be considered for admission.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select a category under which you would want to be considered for admission.',this);" onmouseout="hidetip();" >Non-Sponsored</span>&nbsp;&nbsp;
				<input type='radio'   required="true"   name='NIUMBA_AdmissionCategory' id='NIUMBA_AdmissionCategory1'   value='SPONSORED'    title="Admission category"   onmouseover="showTipOnline('Please select a category under which you would want to be considered for admission.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select a category under which you would want to be considered for admission.',this);" onmouseout="hidetip();" >Sponsored</span>&nbsp;&nbsp;
				<input type='radio'   required="true"   name='NIUMBA_AdmissionCategory' id='NIUMBA_AdmissionCategory2'   value='COMPANY SPONSORED'    title="Admission category"   onmouseover="showTipOnline('Please select a category under which you would want to be considered for admission.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select a category under which you would want to be considered for admission.',this);" onmouseout="hidetip();" >Company Sponsored</span>&nbsp;&nbsp;
				<input type='radio'   required="true"   name='NIUMBA_AdmissionCategory' id='NIUMBA_AdmissionCategory3'   value='NRI'    title="Admission category"   onmouseover="showTipOnline('Please select a category under which you would want to be considered for admission.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select a category under which you would want to be considered for admission.',this);" onmouseout="hidetip();" >NRI</span>&nbsp;&nbsp;
				<input type='radio'   required="true"   name='NIUMBA_AdmissionCategory' id='NIUMBA_AdmissionCategory4'   value='FOREIGN'    title="Admission category"   onmouseover="showTipOnline('Please select a category under which you would want to be considered for admission.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select a category under which you would want to be considered for admission.',this);" onmouseout="hidetip();" >Foreign</span>&nbsp;&nbsp;
				<?php if(isset($NIUMBA_AdmissionCategory) && $NIUMBA_AdmissionCategory!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["NIUMBA_AdmissionCategory"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $NIUMBA_AdmissionCategory;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIUMBA_AdmissionCategory_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Do you suffer from any chronic ailment? If yes give details: </label>
				<div class='fieldBoxLarge'>
				<input type='radio'  onClick="changeAilmentDetails(this);" required="true"   name='NIUMBA_Ailment' id='NIUMBA_Ailment0'   value='Yes'    title="Ailment"   onmouseover="showTipOnline('Do you suffer from any chronic ailment? If yes give details',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Do you suffer from any chronic ailment? If yes give details',this);" onmouseout="hidetip();" >Yes</span>&nbsp;&nbsp;
				<input type='radio'  onClick="changeAilmentDetails(this);" required="true"   name='NIUMBA_Ailment' id='NIUMBA_Ailment1'   value='No'  checked  title="Ailment"   onmouseover="showTipOnline('Do you suffer from any chronic ailment? If yes give details',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Do you suffer from any chronic ailment? If yes give details',this);" onmouseout="hidetip();" >No</span>&nbsp;&nbsp;
				<?php if(isset($NIUMBA_Ailment) && $NIUMBA_Ailment!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["NIUMBA_Ailment"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $NIUMBA_Ailment;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIUMBA_Ailment_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Details: </label>
				<div class='fieldBoxLarge'>
				<input disabled type='text' name='NIUMBA_AilmentDetails' id='NIUMBA_AilmentDetails'  validate="validateStr"    caption="details"   minlength="2"   maxlength="50"      value=''   />
				<?php if(isset($NIUMBA_AilmentDetails) && $NIUMBA_AilmentDetails!=""){ ?>
				  <script>
				      document.getElementById("NIUMBA_AilmentDetails").value = "<?php echo str_replace("\n", '\n', $NIUMBA_AilmentDetails );  ?>";
				      document.getElementById("NIUMBA_AilmentDetails").style.color = "";
					document.getElementById("NIUMBA_AilmentDetails").disabled = false;
					document.getElementById("NIUMBA_AilmentDetails").setAttribute('required','true');				      
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIUMBA_AilmentDetails_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Have you ever been dismissed or put on academic probation? If yes give details.: </label>
				<div class='fieldBoxLarge'>
				<input type='radio' onClick="changeProbationDetails(this);"  required="true"   name='NIUMBA_AcademicProbation' id='NIUMBA_AcademicProbation0'   value='Yes'    title="Probation"   onmouseover="showTipOnline('Have you ever been dismissed or put on academic probation? If yes give details.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Have you ever been dismissed or put on academic probation? If yes give details.',this);" onmouseout="hidetip();" >Yes</span>&nbsp;&nbsp;
				<input type='radio' onClick="changeProbationDetails(this);"  required="true"   name='NIUMBA_AcademicProbation' id='NIUMBA_AcademicProbation1'   value='No'  checked  title="Probation"   onmouseover="showTipOnline('Have you ever been dismissed or put on academic probation? If yes give details.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Have you ever been dismissed or put on academic probation? If yes give details.',this);" onmouseout="hidetip();" >No</span>&nbsp;&nbsp;
				<?php if(isset($NIUMBA_AcademicProbation) && $NIUMBA_AcademicProbation!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["NIUMBA_AcademicProbation"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $NIUMBA_AcademicProbation;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIUMBA_AcademicProbation_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Details: </label>
				<div class='fieldBoxLarge'>
				<input disabled type='text' name='NIUMBA_AcademicProbationDetails' id='NIUMBA_AcademicProbationDetails'  validate="validateStr"    caption="details"   minlength="2"   maxlength="50"      value=''   />
				<?php if(isset($NIUMBA_AcademicProbationDetails) && $NIUMBA_AcademicProbationDetails!=""){ ?>
				  <script>
				      document.getElementById("NIUMBA_AcademicProbationDetails").value = "<?php echo str_replace("\n", '\n', $NIUMBA_AcademicProbationDetails );  ?>";
				      document.getElementById("NIUMBA_AcademicProbationDetails").style.color = "";
					document.getElementById("NIUMBA_AcademicProbationDetails").disabled = false;
					document.getElementById("NIUMBA_AcademicProbationDetails").setAttribute('required','true');				      
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIUMBA_AcademicProbationDetails_error'></div></div>
				</div>
				</div>
			</li>

			<input type='hidden' id='NIUMBA_nationalityChild' name='NIUMBA_nationalityChild' value='<?=$NIUMBA_nationalityChild?>'>
			<input type='hidden' name='NIUMBA_Nationality' id='NIUMBA_Nationality' value='<?=$NIUMBA_Nationality?>'>
			<li>
				<div class='additionalInfoLeftCol'>
				<label>Nationality: </label>
				<div class='fieldBoxLarge'>
				<input disabled type='radio'   required="true"   name='NIUMBA_NationalityField' id='NIUMBA_Nationality0'   value='INDIAN'  title="Nationality"   onmouseover="showTipOnline('Please select your nationality.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select your nationality.',this);" onmouseout="hidetip();" >Indian</span>&nbsp;&nbsp;
				<input disabled type='radio'   required="true"   name='NIUMBA_NationalityField' id='NIUMBA_Nationality1'   value='OTHER'  title="Nationality"   onmouseover="showTipOnline('Please select your nationality.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select your nationality.',this);" onmouseout="hidetip();" >Other</span>&nbsp;&nbsp;
				<?php if(isset($NIUMBA_Nationality) && $NIUMBA_Nationality!=""){ ?>
				  <script>
				        if("<?php echo $NIUMBA_Nationality;?>"=="INDIAN"){
						document.getElementById('NIUMBA_Nationality0').checked = true;
				        }
					else{
						document.getElementById('NIUMBA_Nationality1').checked = true;
					}
				  </script>
				<?php }else if(isset($NIUMBA_nationalityChild) && $NIUMBA_nationalityChild!=""){ ?>
				  <script>
				        if("<?php echo $NIUMBA_nationalityChild;?>"=="INDIAN"){
						document.getElementById('NIUMBA_Nationality0').checked = true;
						document.getElementById('NIUMBA_Nationality').value = 'INDIAN';
				        }
					else{
						document.getElementById('NIUMBA_Nationality1').checked = true;
						document.getElementById('NIUMBA_Nationality').value = 'OTHER';
					}
				  </script>
				<?php } ?>				
				<div style='display:none'><div class='errorMsg' id= 'NIUMBA_Nationality_error'></div></div>
				</div>
				</div>
			</li>

			<div id='passportDetails' style='display:none;'>
			<li>
				<div class='additionalInfoLeftCol'>
				<label>Passport Number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='NIUMBA_passportNumber' id='NIUMBA_passportNumber'  validate="validateStr"    caption="passport number"   minlength="2"   maxlength="20"     tip="Please enter your passport number here. "   title="Passport Number"  value=''   />
				<?php if(isset($NIUMBA_passportNumber) && $NIUMBA_passportNumber!=""){ ?>
				  <script>
				      document.getElementById("NIUMBA_passportNumber").value = "<?php echo str_replace("\n", '\n', $NIUMBA_passportNumber );  ?>";
				      document.getElementById("NIUMBA_passportNumber").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIUMBA_passportNumber_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Issued Date: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='NIUMBA_passportIssueDate' id='NIUMBA_passportIssueDate' readonly minlength='1' maxlength='10'  validate="validateDateForms"   caption='date'          tip="Please enter the date of issue of your passport. If you are not sure about the date, refer your passport."   title="Issued Date"    onmouseover="showTipOnline('Please enter the date of issue of your passport. If you are not sure about the date, refer your passport.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('NIUMBA_passportIssueDate'),'NIUMBA_passportIssueDate_dateImg','dd/MM/yyyy');" />
				&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='NIUMBA_passportIssueDate_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('NIUMBA_passportIssueDate'),'NIUMBA_passportIssueDate_dateImg','dd/MM/yyyy'); return false;" />
				<?php if(isset($NIUMBA_passportIssueDate) && $NIUMBA_passportIssueDate!=""){ ?>
				  <script>
				      document.getElementById("NIUMBA_passportIssueDate").value = "<?php echo str_replace("\n", '\n', $NIUMBA_passportIssueDate );  ?>";
				      document.getElementById("NIUMBA_passportIssueDate").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIUMBA_passportIssueDate_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Valid Till: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='NIUMBA_passportTillDate' id='NIUMBA_passportTillDate' readonly minlength='1' maxlength='10'  validate="validateDateForms" caption='date'        tip="Please enter the valid till date of  your passport. If you are not sure about the date, refer your passport."   title="Valid Till"    onmouseover="showTipOnline('Please enter the valid till date of  your passport. If you are not sure about the date, refer your passport.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('NIUMBA_passportTillDate'),'NIUMBA_passportTillDate_dateImg','dd/MM/yyyy');" />
				&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='NIUMBA_passportTillDate_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('NIUMBA_passportTillDate'),'NIUMBA_passportTillDate_dateImg','dd/MM/yyyy'); return false;" />
				<?php if(isset($NIUMBA_passportTillDate) && $NIUMBA_passportTillDate!=""){ ?>
				  <script>
				      document.getElementById("NIUMBA_passportTillDate").value = "<?php echo str_replace("\n", '\n', $NIUMBA_passportTillDate );  ?>";
				      document.getElementById("NIUMBA_passportTillDate").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIUMBA_passportTillDate_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Issuing country: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='NIUMBA_issuingCountry' id='NIUMBA_issuingCountry'  validate="validateStr"    caption="country"   minlength="2"   maxlength="50"     tip="Please enter the country from where your passport was issued"   title="Issuing country"  value=''   />
				<?php if(isset($NIUMBA_issuingCountry) && $NIUMBA_issuingCountry!=""){ ?>
				  <script>
				      document.getElementById("NIUMBA_issuingCountry").value = "<?php echo str_replace("\n", '\n', $NIUMBA_issuingCountry );  ?>";
				      document.getElementById("NIUMBA_issuingCountry").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIUMBA_issuingCountry_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Visa Type: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='NIUMBA_visaType' id='NIUMBA_visaType'  validate="validateStr"    caption="visa type"   minlength="2"   maxlength="50"     tip="Please enter the type of visa you've been issued for your stay in India. If you are unsure about the type of vise, please refer your passport."   title="Visa Type"  value=''   />
				<?php if(isset($NIUMBA_visaType) && $NIUMBA_visaType!=""){ ?>
				  <script>
				      document.getElementById("NIUMBA_visaType").value = "<?php echo str_replace("\n", '\n', $NIUMBA_visaType );  ?>";
				      document.getElementById("NIUMBA_visaType").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIUMBA_visaType_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol' style="width:800px;">
				<label>Please enter the time period since when you've been living in India: </label>
				<div class='fieldBoxLarge' style="width:300px;">
				Year: 
				<input style="width:50px;" type='text' name='NIUMBA_timePeriodYear' id='NIUMBA_timePeriodYear'  validate="validateInteger"    caption="year"   minlength="1"   maxlength="2"     tip="Please enter the time period since when you've been living in india."   title="Please enter the time period since when you've been living in india."  value=''   />
				<?php if(isset($NIUMBA_timePeriodYear) && $NIUMBA_timePeriodYear!=""){ ?>
				  <script>
				      document.getElementById("NIUMBA_timePeriodYear").value = "<?php echo str_replace("\n", '\n', $NIUMBA_timePeriodYear );  ?>";
				      document.getElementById("NIUMBA_timePeriodYear").style.color = "";
				  </script>
				<?php } ?>
				
				Month:
				<input style="width:50px;" type='text' name='NIUMBA_timePeriodMonth' id='NIUMBA_timePeriodMonth'  validate="validateInteger"    caption="month"   minlength="1"   maxlength="2"     tip="Please enter the time period since when you've been living in india."   title="Please enter the time period since when you've been living in india."  value=''   />
				<?php if(isset($NIUMBA_timePeriodMonth) && $NIUMBA_timePeriodMonth!=""){ ?>
				  <script>
				      document.getElementById("NIUMBA_timePeriodMonth").value = "<?php echo str_replace("\n", '\n', $NIUMBA_timePeriodMonth );  ?>";
				      document.getElementById("NIUMBA_timePeriodMonth").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIUMBA_timePeriodYear_error'></div></div>
				<div style='display:none'><div class='errorMsg' id= 'NIUMBA_timePeriodMonth_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Select the exam that you've taken: </label>
				<div class='fieldBoxLarge'>
				<input type='checkbox'  onChange='checkExamTaken(this);'   caption="exam"   name='NIUMBA_ExamTaken[]' id='NIUMBA_ExamTaken0'   value='TOEFL'   title="Select the exam that you've taken"   onmouseover="showTipOnline('Please select the exam that you\'ve appeared for',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select the exam that you\'ve appeared for',this);" onmouseout="hidetip();" >Toefl</span>&nbsp;&nbsp;
				<input type='checkbox'  onChange='checkExamTaken(this);'    caption="exam"   name='NIUMBA_ExamTaken[]' id='NIUMBA_ExamTaken1'   value='IELTS'    title="Select the exam that you've taken"   onmouseover="showTipOnline('Please select the exam that you\'ve appeared for',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select the exam that you\'ve appeared for',this);" onmouseout="hidetip();" >IELTS</span>&nbsp;&nbsp;
				<input type='checkbox'  onChange='checkExamTaken(this);'    caption="exam"   name='NIUMBA_ExamTaken[]' id='NIUMBA_ExamTaken2'   value='SAT'    title="Select the exam that you've taken"   onmouseover="showTipOnline('Please select the exam that you\'ve appeared for',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select the exam that you\'ve appeared for',this);" onmouseout="hidetip();" >SAT</span>&nbsp;&nbsp;
				<?php if(isset($NIUMBA_ExamTaken) && $NIUMBA_ExamTaken!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["NIUMBA_ExamTaken[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$NIUMBA_ExamTaken);
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
				
				<div style='display:none'><div class='errorMsg' id= 'NIUMBA_ExamTaken_error'></div></div>
				</div>
				</div>
			</li>

			<li id='TOEFLDetails' style='display: none;'>
				<div class='additionalInfoLeftCol'>
				<label>TOEFL Exam Roll Number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='NIUMBA_ExamRollNumber' id='NIUMBA_ExamRollNumber'  validate="validateStr"    caption="number"   minlength="2"   maxlength="20"     tip="Enter your exam roll number for the selected examination"   title="Exam Roll Number"  value=''   />
				<?php if(isset($NIUMBA_ExamRollNumber) && $NIUMBA_ExamRollNumber!=""){ ?>
				  <script>
				      document.getElementById("NIUMBA_ExamRollNumber").value = "<?php echo str_replace("\n", '\n', $NIUMBA_ExamRollNumber );  ?>";
				      document.getElementById("NIUMBA_ExamRollNumber").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIUMBA_ExamRollNumber_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>TOEFL Exam score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='NIUMBA_ExamScore' id='NIUMBA_ExamScore'  validate="validateFloat"    caption="score"   minlength="1"   maxlength="10"     tip="Enter your exam score for the selected examination"   title="Exam score"  value=''   />
				<?php if(isset($NIUMBA_ExamScore) && $NIUMBA_ExamScore!=""){ ?>
				  <script>
				      document.getElementById("NIUMBA_ExamScore").value = "<?php echo str_replace("\n", '\n', $NIUMBA_ExamScore );  ?>";
				      document.getElementById("NIUMBA_ExamScore").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIUMBA_ExamScore_error'></div></div>
				</div>
				</div>
			</li>
			<li id='IELTSDetails' style='display: none;'>
				<div class='additionalInfoLeftCol'>
				<label>IELTS Exam Roll Number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='NIUMBA_ExamRollNumberIELTS' id='NIUMBA_ExamRollNumberIELTS'  validate="validateStr"    caption="number"   minlength="2"   maxlength="20"     tip="Enter your exam roll number for the selected examination"   title="Exam Roll Number"  value=''   />
				<?php if(isset($NIUMBA_ExamRollNumberIELTS) && $NIUMBA_ExamRollNumberIELTS!=""){ ?>
				  <script>
				      document.getElementById("NIUMBA_ExamRollNumberIELTS").value = "<?php echo str_replace("\n", '\n', $NIUMBA_ExamRollNumberIELTS );  ?>";
				      document.getElementById("NIUMBA_ExamRollNumberIELTS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIUMBA_ExamRollNumberIELTS_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>IELTS Exam score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='NIUMBA_ExamScoreIELTS' id='NIUMBA_ExamScoreIELTS'  validate="validateFloat"    caption="score"   minlength="1"   maxlength="10"     tip="Enter your exam score for the selected examination"   title="Exam score"  value=''   />
				<?php if(isset($NIUMBA_ExamScoreIELTS) && $NIUMBA_ExamScoreIELTS!=""){ ?>
				  <script>
				      document.getElementById("NIUMBA_ExamScoreIELTS").value = "<?php echo str_replace("\n", '\n', $NIUMBA_ExamScoreIELTS );  ?>";
				      document.getElementById("NIUMBA_ExamScoreIELTS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIUMBA_ExamScoreIELTS_error'></div></div>
				</div>
				</div>
			</li>
			<li id='SATDetails' style='display: none;'>
				<div class='additionalInfoLeftCol'>
				<label>SAT Exam Roll Number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='NIUMBA_ExamRollNumberSAT' id='NIUMBA_ExamRollNumberSAT'  validate="validateStr"    caption="number"   minlength="2"   maxlength="20"     tip="Enter your exam roll number for the selected examination"   title="Exam Roll Number"  value=''   />
				<?php if(isset($NIUMBA_ExamRollNumberSAT) && $NIUMBA_ExamRollNumberSAT!=""){ ?>
				  <script>
				      document.getElementById("NIUMBA_ExamRollNumberSAT").value = "<?php echo str_replace("\n", '\n', $NIUMBA_ExamRollNumberSAT );  ?>";
				      document.getElementById("NIUMBA_ExamRollNumberSAT").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIUMBA_ExamRollNumberSAT_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>SAT Exam score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='NIUMBA_ExamScoreSAT' id='NIUMBA_ExamScoreSAT'  validate="validateFloat"    caption="score"   minlength="1"   maxlength="10"     tip="Enter your exam score for the selected examination"   title="Exam score"  value=''   />
				<?php if(isset($NIUMBA_ExamScoreSAT) && $NIUMBA_ExamScoreSAT!=""){ ?>
				  <script>
				      document.getElementById("NIUMBA_ExamScoreSAT").value = "<?php echo str_replace("\n", '\n', $NIUMBA_ExamScoreSAT );  ?>";
				      document.getElementById("NIUMBA_ExamScoreSAT").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIUMBA_ExamScoreSAT_error'></div></div>
				</div>
				</div>
			</li>
			
			<?php
			    if(isset($NIUMBA_ExamTaken) && $NIUMBA_ExamTaken!=""){
				if(strpos($NIUMBA_ExamTaken,'TOEFL')!==false)
					echo "<script>checkExamTaken(document.getElementById('NIUMBA_ExamTaken0'));</script>";
				if(strpos($NIUMBA_ExamTaken,'IELTS')!==false)
					echo "<script>checkExamTaken(document.getElementById('NIUMBA_ExamTaken1'));</script>";
				if(strpos($NIUMBA_ExamTaken,'SAT')!==false)
					echo "<script>checkExamTaken(document.getElementById('NIUMBA_ExamTaken2'));</script>";				
			    }
			?>			
			</div>

			<!-- Family Detail section -->
			<li>
				<h3 class="upperCase">Personal Information</h3>
				</li>
				<li>
				<div class='additionalInfoLeftCol'>
				<label>Father's education level: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='NIUMBA_fatherEducation' id='NIUMBA_fatherEducation'  validate="validateStr"   required="true"   caption="education"   minlength="2"   maxlength="50"     tip="Please enter your father's education level.  Enter the class till where your father has studied."   title="Father's education level"  value=''   />
				<?php if(isset($NIUMBA_fatherEducation) && $NIUMBA_fatherEducation!=""){ ?>
				  <script>
				      document.getElementById("NIUMBA_fatherEducation").value = "<?php echo str_replace("\n", '\n', $NIUMBA_fatherEducation );  ?>";
				      document.getElementById("NIUMBA_fatherEducation").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIUMBA_fatherEducation_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Father's email: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='NIUMBA_fatherEmail' id='NIUMBA_fatherEmail'  validate="validateEmail"   required="true"   caption="email"   minlength="2"   maxlength="50"     tip="Please enter your father's email address. If your father doesn't have an email address, just enter <b>NA</b>."   title="Father's email"  value=''  allowNA="true" />
				<?php if(isset($NIUMBA_fatherEmail) && $NIUMBA_fatherEmail!=""){ ?>
				  <script>
				      document.getElementById("NIUMBA_fatherEmail").value = "<?php echo str_replace("\n", '\n', $NIUMBA_fatherEmail );  ?>";
				      document.getElementById("NIUMBA_fatherEmail").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIUMBA_fatherEmail_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Father's Mobile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='NIUMBA_fatherMobile' id='NIUMBA_fatherMobile'  validate="validateMobileInteger"   required="true"   caption="mobile number"   minlength="10"   maxlength="10"     tip="Please enter the 10 digit mobile number of your father."   title="Father's Mobile"  value=''   />
				<?php if(isset($NIUMBA_fatherMobile) && $NIUMBA_fatherMobile!=""){ ?>
				  <script>
				      document.getElementById("NIUMBA_fatherMobile").value = "<?php echo str_replace("\n", '\n', $NIUMBA_fatherMobile );  ?>";
				      document.getElementById("NIUMBA_fatherMobile").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIUMBA_fatherMobile_error'></div></div>
				</div>
				</div>
				
				<div class='additionalInfoRightCol'>
				<label>Father's Annual Income: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='NIUMBA_fatherIncome' id='NIUMBA_fatherIncome'  validate="validateFloat"   required="true"   caption="income"   minlength="2"   maxlength="12"     tip="Please enter your Father's Annual Income in Rupees. If its not applicable in your case, just enter <b>NA</b>."   title="Father's annual income"  value=''  allowNA="true" />
				<?php if(isset($NIUMBA_fatherIncome) && $NIUMBA_fatherIncome!=""){ ?>
				  <script>
				      document.getElementById("NIUMBA_fatherIncome").value = "<?php echo str_replace("\n", '\n', $NIUMBA_fatherIncome );  ?>";
				      document.getElementById("NIUMBA_fatherIncome").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIUMBA_fatherIncome_error'></div></div>
				</div>
				</div>
				
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Mother's education level: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='NIUMBA_motherEducation' id='NIUMBA_motherEducation'  validate="validateStr"   required="true"   caption="education"   minlength="2"   maxlength="50"     tip="Please enter your mother's education level.  Enter the class till where your mother has studied."   title="Mother's education level"  value=''   />
				<?php if(isset($NIUMBA_motherEducation) && $NIUMBA_motherEducation!=""){ ?>
				  <script>
				      document.getElementById("NIUMBA_motherEducation").value = "<?php echo str_replace("\n", '\n', $NIUMBA_motherEducation );  ?>";
				      document.getElementById("NIUMBA_motherEducation").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIUMBA_motherEducation_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Mother's email: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='NIUMBA_motherEmail' id='NIUMBA_motherEmail'  validate="validateEmail"   required="true"   caption="email"   minlength="2"   maxlength="50"     tip="Please enter your mother's email address. If your mother doesn't have an email address, just enter <b>NA</b>."   title="Mother's email"  value=''  allowNA="true" />
				<?php if(isset($NIUMBA_motherEmail) && $NIUMBA_motherEmail!=""){ ?>
				  <script>
				      document.getElementById("NIUMBA_motherEmail").value = "<?php echo str_replace("\n", '\n', $NIUMBA_motherEmail );  ?>";
				      document.getElementById("NIUMBA_motherEmail").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIUMBA_motherEmail_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Mother's Mobile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='NIUMBA_motherMobile' id='NIUMBA_motherMobile'  validate="validateMobileInteger"   required="true"   caption="mobile number"   minlength="10"   maxlength="10"     tip="Please enter the 10 digit mobile number of your mother. If your mother doesn't have a mobile, just enter <b>NA</b>."   title="Mother's Mobile"  value=''  allowNA="true" />
				<?php if(isset($NIUMBA_motherMobile) && $NIUMBA_motherMobile!=""){ ?>
				  <script>
				      document.getElementById("NIUMBA_motherMobile").value = "<?php echo str_replace("\n", '\n', $NIUMBA_motherMobile );  ?>";
				      document.getElementById("NIUMBA_motherMobile").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIUMBA_motherMobile_error'></div></div>
				</div>
				</div>
				
				<div class='additionalInfoRightCol'>
				<label>Mother's Annual Income: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='NIUMBA_motherIncome' id='NIUMBA_motherIncome'  validate="validateFloat"   required="true"   caption="income"   minlength="2"   maxlength="12"     tip="Please enter your Mother's Annual Income in Rupees. If its not applicable in your case, just enter <b>NA</b>."   title="Mother's annual income"  value=''  allowNA="true" />
				<?php if(isset($NIUMBA_motherIncome) && $NIUMBA_motherIncome!=""){ ?>
				  <script>
				      document.getElementById("NIUMBA_motherIncome").value = "<?php echo str_replace("\n", '\n', $NIUMBA_motherIncome );  ?>";
				      document.getElementById("NIUMBA_motherIncome").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIUMBA_motherIncome_error'></div></div>
				</div>
				</div>
				
			</li>
			<!-- Family Detail section -->

			<!-- Education details -->
			<li>
				<h3 class="upperCase">Additional education details</h3>
				<div class='additionalInfoLeftCol'>
				<label>Class 10th Subjects: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='NIUMBA_10thSubjects' id='NIUMBA_10thSubjects'  validate="validateStr"   required="true"   caption="subjects"   minlength="2"   maxlength="50"     tip="Please enter the name of subjects that you studied in class 10th, seperated by a comma. For example English, Hindi, Mathematics, Science etc."   title="Class 10th Subjects"  value=''   csv="true" />
				<?php if(isset($NIUMBA_10thSubjects) && $NIUMBA_10thSubjects!=""){ ?>
				  <script>
				      document.getElementById("NIUMBA_10thSubjects").value = "<?php echo str_replace("\n", '\n', $NIUMBA_10thSubjects );  ?>";
				      document.getElementById("NIUMBA_10thSubjects").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIUMBA_10thSubjects_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Class 12th Subjects: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='NIUMBA_12thSubjects' id='NIUMBA_12thSubjects'  validate="validateStr"   required="true"   caption="subjects"   minlength="2"   maxlength="50"     tip="Please enter the name of subjects that you studied in class 12th, seperated by a comma. For example English, Hindi, Mathematics, Physics etc."   title="Class 12th Subjects"  value=''   csv="true" />
				<?php if(isset($NIUMBA_12thSubjects) && $NIUMBA_12thSubjects!=""){ ?>
				  <script>
				      document.getElementById("NIUMBA_12thSubjects").value = "<?php echo str_replace("\n", '\n', $NIUMBA_12thSubjects );  ?>";
				      document.getElementById("NIUMBA_12thSubjects").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIUMBA_12thSubjects_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Graduation Subjects: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='NIUMBA_graduationSubjects' id='NIUMBA_graduationSubjects'  validate="validateStr"   required="true"   caption="subjects"   minlength="2"   maxlength="50"     tip="Please enter the name of subjects that you studied in graduation, seperated by a comma. For example English, Hindi, Mathematics, Science etc."   title="Graduation Subjects"  value=''   csv="true" />
				<?php if(isset($NIUMBA_graduationSubjects) && $NIUMBA_graduationSubjects!=""){ ?>
				  <script>
				      document.getElementById("NIUMBA_graduationSubjects").value = "<?php echo str_replace("\n", '\n', $NIUMBA_graduationSubjects );  ?>";
				      document.getElementById("NIUMBA_graduationSubjects").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIUMBA_graduationSubjects_error'></div></div>
				</div>
				</div>
			</li>
			
			<?php
			
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
	foreach($otherCourses as $key=>$course){
	?>
	<li>
				<div class='additionalInfoleftCol'>
				<label style="font-weight: normal;"><?=$course?> Subjects: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='NIUMBA_mul_<?=$key?>_Subjects' id='NIUMBA_mul_<?=$key?>_Subjects'  maxlength="200" minlength="2" validate="validateStr" caption="subjects"   required="true"        tip="Please enter the name of subjects that you studied in <?=$course?>, seperated by a comma. For example English, Hindi, Mathematics, Science etc."   title="<?=$course?> Subjects"  value=''   csv="true" />
				<?php if(${'NIUMBA_mul_'.$key.'_Subjects'}){ ?>
				  <script>
				      document.getElementById("NIUMBA_mul_<?=$key?>_Subjects").value = "<?php echo str_replace("\n", '\n', ${'NIUMBA_mul_'.$key.'_Subjects'} );  ?>";
				      document.getElementById("NIUMBA_mul_<?=$key?>_Subjects").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIUMBA_mul_<?=$key?>_Subjects_error'></div></div>
				</div>
				</div>
	</li>
	<?php
	}
	?>
				
			<!-- Education details -->
<?php endif; ?>
			
			<li style="width:100%">
				<h3 class="upperCase">Qualifying exam details</h3>
                        <div class="clearFix"></div>
                        
                        <div class="additionalInfoLeftCol" style="width:100%">
			<label>Qualifying examinations:&nbsp;</label>
			<div class='fieldBoxLarge' style="width:600px">
                        <input name='courseNIUMBA[]' value='CAT' id='courseNIUMBA0' type="checkbox" onClick="toggleExamDetails('cat','0');" validate="validateCheckedGroup"   required="true"   caption="exam" /> CAT &nbsp;&nbsp;&nbsp;&nbsp;
                        <input name='courseNIUMBA[]' value='MAT' id="courseNIUMBA1" type="checkbox" onClick="toggleExamDetails('mat','1');" validate="validateCheckedGroup"   required="true"   caption="exam"  /> MAT &nbsp;&nbsp;&nbsp;&nbsp;
                        <input name='courseNIUMBA[]' value='XAT' id="courseNIUMBA2" type="checkbox" onClick="toggleExamDetails('xat','2');" validate="validateCheckedGroup"   required="true"   caption="exam" /> XAT &nbsp;&nbsp;&nbsp;&nbsp;  
			<input name='courseNIUMBA[]' value='ATMA' id="courseNIUMBA3" type="checkbox" onClick="toggleExamDetails('atma','3');" validate="validateCheckedGroup"   required="true"   caption="exam" /> ATMA &nbsp;&nbsp;&nbsp;&nbsp;
                        <input name='courseNIUMBA[]'  value='GMAT' id="courseNIUMBA4" type="checkbox" onClick="toggleExamDetails('gmat','4');" validate="validateCheckedGroup"   required="true"   caption="exam" /> GMAT &nbsp;&nbsp;&nbsp;&nbsp;   
			<div class="clearFix"></div>
                    	<div style='display:none'><div class='errorMsg' id= 'courseNIUMBA_error'></div></div>
                    </div>

		   
			</div>
			</li>
			<li id="catDetails" style="display:none;">
				<h3>CAT Exam Details</h3>
				<div class='additionalInfoLeftCol'>
				<label>Date of Examination: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='catDateOfExaminationAdditional' id='catDateOfExaminationAdditional' readonly minlength='1' maxlength='10'  validate="validateDateForms"         tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('catDateOfExaminationAdditional'),'catDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" caption='date'/>
				&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='catDateOfExaminationAdditional_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('catDateOfExaminationAdditional'),'catDateOfExaminationAdditional_dateImg','dd/MM/yyyy'); return false;" />
				<?php if(isset($catDateOfExaminationAdditional) && $catDateOfExaminationAdditional!=""){ ?>
				  <script>
				      document.getElementById("catDateOfExaminationAdditional").value = "<?php echo str_replace("\n", '\n', $catDateOfExaminationAdditional );  ?>";
				      document.getElementById("catDateOfExaminationAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'catDateOfExaminationAdditional_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Roll Number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='catRollNumberAdditional' id='catRollNumberAdditional'   validate="validateStr"    caption="Roll Number"   minlength="2"   maxlength="50"    tip="Mention your roll number for the exam. If you have not appeared for this examination enter <b>NA.</b>"   value=''   allowNA='true' />
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
			<li id="catDetails1" style="display:none;">
                                <div class='additionalInfoLeftCol'>
                                <label>Score: </label>
                                <div class='fieldBoxLarge'>
                                <input type='text' name='catScoreAdditional' id='catScoreAdditional'     caption="score" minlength="1" maxlength="5" validate="validateFloat"      tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter NA."   value=''  allowNA="true"  />
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
				<label>Percentile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='catPercentileAdditional' id='catPercentileAdditional'   validate="validateFloat"    caption="Percentile"   minlength="1"   maxlength="5"         tip="Mention your percentile in the exam. If you don't know your percentile, enter <b>NA.</b>"   value=''   allowNA='true' />
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

			<li id="matDetails" style="display:none;">
				<h3>MAT Exam Details</h3>
				<div class='additionalInfoLeftCol'>
				<label>Date of Examination: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='matDateOfExaminationAdditional' id='matDateOfExaminationAdditional' readonly minlength='1' maxlength='10'  validate="validateDateForms"         tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('matDateOfExaminationAdditional'),'matDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" caption='date' />
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

				<div class='additionalInfoRightCol'>
				<label>Roll Number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='matRollNumberAdditional' id='matRollNumberAdditional'   validate="validateStr"    caption="Roll Number"   minlength="2"   maxlength="50"        tip="Mention your roll number for the exam. If you have not appeared for this examination enter <b>NA.</b>"   value=''    allowNA='true'/>
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
			<li id="matDetails1" style="display:none;">

                                <div class='additionalInfoLeftCol'>
                                <label>Score: </label>
                                <div class='fieldBoxLarge'>
                                <input type='text' name='matScoreAdditional' id='matScoreAdditional'           tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter NA."   value=''  allowNA='true' caption='score' minlength=1 maxlength=5 validate='validateFloat' />
                                <?php if(isset($matScoreAdditional) && $matScoreAdditional!=""){ ?>
                                  <script>
                                      document.getElementById("matScoreAdditional").value = "<?php echo str_replace("\n", '\n', $matScoreAdditional );  ?>";
                                      document.getElementById("matScoreAdditional").style.color = "";
                                  </script>
                                <?php } ?>

                                <div style='display:none'><div class='errorMsg' id= 'matScoreAdditional_error'></div></div>
                                </div>
                                </div>
			
				<div class='additionalInfoRightCol'>
				<label>Percentile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='matPercentileAdditional' id='matPercentileAdditional'   validate="validateFloat"    caption="Percentile"   minlength="1"   maxlength="5"       tip="Mention your percentile in the exam. If you don't know your percentile, you can leave this field blank, enter <b>NA.</b>"   value=''    allowNA='true'/>
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
			<li id="xatDetails" style="display:none;">
				<h3>XAT Exam Details</h3>
				<div class='additionalInfoLeftCol'>
				<label>Date of Examination: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='xatDateOfExaminationAdditional' id='xatDateOfExaminationAdditional' readonly minlength='1' maxlength='10'  validate="validateDateForms"         tip="Choose the date on which the examination was conducted."     onmouseover="showTipOnline('Choose the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('xatDateOfExaminationAdditional'),'xatDateOfExaminationAdditional_dateImg','dd/MM/yyyy');"  caption='date' />
				&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='xatDateOfExaminationAdditional_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('xatDateOfExaminationAdditional'),'xatDateOfExaminationAdditional_dateImg','dd/MM/yyyy'); return false;" />
				<?php if(isset($xatDateOfExaminationAdditional) && $xatDateOfExaminationAdditional!=""){ ?>
				  <script>
				      document.getElementById("xatDateOfExaminationAdditional").value = "<?php echo str_replace("\n", '\n', $xatDateOfExaminationAdditional );  ?>";
				      document.getElementById("xatDateOfExaminationAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'xatDateOfExaminationAdditional_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Roll Number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='xatRollNumberAdditional' id='xatRollNumberAdditional'  validate="validateStr"    caption="Roll Number"   minlength="2"   maxlength="50"     tip="Mention your roll number for the exam. If you have not appeared for this examination enter <b>NA.</b>"   value=''   allowNA='true' />
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
			<li id="xatDetails1" style="display:none;">
				<div class='additionalInfoLeftCol'>
				    <label>Score: </label>
				    <div class='fieldBoxLarge'>
					<input type='text' name='xatScoreAdditional' id='xatScoreAdditional'          tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter NA."   value=''  allowNA='true' caption='score' minlength=1 maxlength=5 validate='validateFloat' />
					<?php if(isset($xatScoreAdditional) && $xatScoreAdditional!=""){ ?>
					<script>
					    document.getElementById("xatScoreAdditional").value = "<?php echo str_replace("\n", '\n', $xatScoreAdditional );  ?>";
					    document.getElementById("xatScoreAdditional").style.color = "";
					</script>
					<?php } ?>
		
					<div style='display:none'><div class='errorMsg' id= 'xatScoreAdditional_error'></div></div>
				    </div>
				</div>
		
				<div class='additionalInfoRightCol'>
				<label>Percentile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='xatPercentileAdditional' id='xatPercentileAdditional'  validate="validateFloat"    caption="Percentile"   minlength="1"   maxlength="5"     tip="Mention your percentile in the exam. If you don't know your percentile, enter <b>NA.</b>"   value=''  allowNA='true'  />
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

			

			<li id="atmaDetails" style="display:none">
				<h3>ATMA Exam Details</h3>
				<div class='additionalInfoLeftCol'>
				<label>Date of Examination: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='atmaDateOfExaminationAdditional' id='atmaDateOfExaminationAdditional' readonly minlength='1' maxlength='10'  validate="validateDateForms"         tip="Choose the date on which the examination was conducted."     onmouseover="showTipOnline('Choose the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('atmaDateOfExaminationAdditional'),'atmaDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" caption='date' />
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

			<div class='additionalInfoRightCol'>
				<label>Roll Number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='atmaRollNumberAdditional' id='atmaRollNumberAdditional'  validate="validateStr"    caption="Roll Number"   minlength="2"   maxlength="50"     tip="Mention your roll number for the exam. If you have not appeared for this examination enter <b>NA.</b>"   value=''  allowNA='true'  />
				<?php if(isset($atmaRollNumberAdditional) && $atmaRollNumberAdditional!=""){ ?>
				  <script>
				      document.getElementById("atmaRollNumberAdditional").value = "<?php echo str_replace("\n", '\n', $atmaRollNumberAdditional );  ?>";
				      document.getElementById("atmaRollNumberAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'atmaRollNumberAdditional_error'></div></div>
				</div>
				</div>

			</li>
			<li id="atmaDetails1" style="display:none;">
                                <div class='additionalInfoLeftCol'>
                                <label>Score: </label>
                                <div class='fieldBoxLarge'>
                                <input type='text' name='atmaScoreAdditional' id='atmaScoreAdditional'         tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter NA."   value=''  allowNA='true' caption='score' minlength=1 maxlength=5 validate='validateFloat' />
                                <?php if(isset($atmaScoreAdditional) && $atmaScoreAdditional!=""){ ?>
                                  <script>
                                      document.getElementById("atmaScoreAdditional").value = "<?php echo str_replace("\n", '\n', $atmaScoreAdditional );  ?>";
                                      document.getElementById("atmaScoreAdditional").style.color = "";
                                  </script>
                                <?php } ?>

                                <div style='display:none'><div class='errorMsg' id= 'atmaScoreAdditional_error'></div></div>
                                </div>
                                </div>
			
				<div class='additionalInfoRightCol'>
				<label>Percentile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='atmaPercentileAdditional' id='atmaPercentileAdditional'  validate="validateFloat"    caption="Percentile"   minlength="1"   maxlength="5"     tip="Mention your percentile in the exam. If you don't know your percentile, enter <b>NA.</b>"   value=''   allowNA='true' />
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
			<li id="gmatDetails" style="display:none">
				<h3>GMAT Exam Details</h3>
				<div class='additionalInfoLeftCol'>
				<label>Date of Examination: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gmatDateOfExaminationAdditional' id='gmatDateOfExaminationAdditional' readonly minlength='1' maxlength='10'  validate="validateDateForms"         tip="Choose the date on which the examination was conducted."     onmouseover="showTipOnline('Choose the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('gmatDateOfExaminationAdditional'),'gmatDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" caption='date' />
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
			<div class='additionalInfoRightCol'>
				<label>Roll Number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gmatRollNumberAdditional' id='gmatRollNumberAdditional'  validate="validateStr"    caption="Roll Number"   minlength="2"   maxlength="50"     tip="Mention your roll number for the exam. If you have not appeared for this examination enter <b>NA.</b>"   value=''   allowNA='true' />
				<?php if(isset($gmatRollNumberAdditional) && $gmatRollNumberAdditional!=""){ ?>
				  <script>
				      document.getElementById("gmatRollNumberAdditional").value = "<?php echo str_replace("\n", '\n', $gmatRollNumberAdditional );  ?>";
				      document.getElementById("gmatRollNumberAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gmatRollNumberAdditional_error'></div></div>
				</div>
				</div>

			</li>
			<li id="gmatDetails1" style="display:none;">
				<div class='additionalInfoLeftCol'>
				    <label>Score: </label>
				    <div class='fieldBoxLarge'>
					<input type='text' name='gmatScoreAdditional' id='gmatScoreAdditional'         tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter NA."   value=''  allowNA='true' caption='score' minlength=1 maxlength=5 validate='validateFloat' />
					<?php if(isset($gmatScoreAdditional) && $gmatScoreAdditional!=""){ ?>
					<script>
					    document.getElementById("gmatScoreAdditional").value = "<?php echo str_replace("\n", '\n', $gmatScoreAdditional );  ?>";
					    document.getElementById("gmatScoreAdditional").style.color = "";
					</script>
					<?php } ?>
		
					<div style='display:none'><div class='errorMsg' id= 'gmatScoreAdditional_error'></div></div>
				    </div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Percentile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gmatPercentileAdditional' id='gmatPercentileAdditional'  validate="validateFloat"    caption="percentile"   minlength="1"   maxlength="7"     tip="Mention your percentile in the exam. If you don't know your percentile, enter <b>NA.</b>"   value=''   allowNA='true' />
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


<?php if($action != 'updateScore'):?>

			<li>
				<h3 class="upperCase">Other Details</h3>
				</li>
				<li>
				<div class='additionalInfoLeftCol'>
				<label>Hostel accomodation required?: </label>
				<div class='fieldBoxLarge'>
				<input type='radio'   required="true"   name='NIUMBA_hostel' id='NIUMBA_hostel0'   value='Yes'  checked  title="Hostel accomodation required?"   onmouseover="showTipOnline('Please mention if you require hostel accomodation',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please mention if you require hostel accomodation',this);" onmouseout="hidetip();" >Yes</span>&nbsp;&nbsp;
				<input type='radio'   required="true"   name='NIUMBA_hostel' id='NIUMBA_hostel1'   value='No'    title="Hostel accomodation required?"   onmouseover="showTipOnline('Please mention if you require hostel accomodation',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please mention if you require hostel accomodation',this);" onmouseout="hidetip();" >No</span>&nbsp;&nbsp;
				<?php if(isset($NIUMBA_hostel) && $NIUMBA_hostel!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["NIUMBA_hostel"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $NIUMBA_hostel;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIUMBA_hostel_error'></div></div>
				</div>
				</div>
			</li>


			<li>
				<div class='additionalInfoLeftCol'>
				<label>Type of hostel accomodation required: </label>
				<div class='fieldBoxLarge'>
				<input type='radio'   required="true"   name='NIUMBA_AC_NONAC' id='NIUMBA_AC_NONAC0'   value='AC'  checked  title="Type of hostel accomodation required"   onmouseover="showTipOnline('Please mention the type of hostel accomodation that you require',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please mention the type of hostel accomodation that you require',this);" onmouseout="hidetip();" >AC</span>&nbsp;&nbsp;
				<input type='radio'   required="true"   name='NIUMBA_AC_NONAC' id='NIUMBA_AC_NONAC1'   value='Non AC'    title="Type of hostel accomodation required"   onmouseover="showTipOnline('Please mention the type of hostel accomodation that you require',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please mention the type of hostel accomodation that you require',this);" onmouseout="hidetip();" >Non AC</span>&nbsp;&nbsp;
				<?php if(isset($NIUMBA_AC_NONAC) && $NIUMBA_AC_NONAC!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["NIUMBA_AC_NONAC"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $NIUMBA_AC_NONAC;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIUMBA_AC_NONAC_error'></div></div>
				</div>
				</div>
			</li>


			<li>
				<div class='additionalInfoLeftCol'>
				<label>Transport Required?: </label>
				<div class='fieldBoxLarge'>
				<input type='radio'   required="true"   name='NIUMBA_transportRequired' id='NIUMBA_transportRequired0'   value='Yes'  checked  title="Transport Required?"   onmouseover="showTipOnline('Please mention if you require transport for commuting to and from the institute',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please mention if you require transport for commuting to and from the institute',this);" onmouseout="hidetip();" >Yes</span>&nbsp;&nbsp;
				<input type='radio'   required="true"   name='NIUMBA_transportRequired' id='NIUMBA_transportRequired1'   value='No'    title="Transport Required?"   onmouseover="showTipOnline('Please mention if you require transport for commuting to and from the institute',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please mention if you require transport for commuting to and from the institute',this);" onmouseout="hidetip();" >No</span>&nbsp;&nbsp;
				<?php if(isset($NIUMBA_transportRequired) && $NIUMBA_transportRequired!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["NIUMBA_transportRequired"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $NIUMBA_transportRequired;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIUMBA_transportRequired_error'></div></div>
				</div>
				</div>
			</li>


			<li>
				<h3 class="upperCase">Achievements</h3>
				</li>
				<li>
				<div class='additionalInfoLeftCol'>
				<label>Have you ever been awarded a scholarship? If yes, give details: </label>
				<div class='fieldBoxLarge'>
				<textarea style='width: 624px;' name='NIUMBA_scholarship' id='NIUMBA_scholarship'  validate="validateStr"    caption="details"   minlength="2"   maxlength="1000"     tip="If you were ever awarded a scholarship, please provide complete details of the scholarship here. If not, then leave this field blank."   title="Have you ever been awarded a scholarship? If yes, give details"   ></textarea>
				<?php if(isset($NIUMBA_scholarship) && $NIUMBA_scholarship!=""){ ?>
				  <script>
				      document.getElementById("NIUMBA_scholarship").value = "<?php echo str_replace("\n", '\n', $NIUMBA_scholarship );  ?>";
				      document.getElementById("NIUMBA_scholarship").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIUMBA_scholarship_error'></div></div>
				</div>
				</div>
			</li>

			<table width="100%" style="margin-top:10px;margin-bottom:10px;border:1px solid #000" >
				<tr class="cutom-tr">
					<td width='120px'>
						<div class="fieldBoxLarge"></div>
					</td>
					<td>
						Award Name
					</td>
					<td>
						Year
					</td>
					<td>
						For
					</td>
				</tr>
				<tr class="cutom-tr">
					<td>
						<div class="fieldBoxLarge">First</div>
					</td>
					<td>
						<div class='fieldBoxLarge'>
						<input type='text' name='NIUMBA_awards1Name' id='NIUMBA_awards1Name'  validate="validateStr"    caption="details"   minlength="2"   maxlength="100"     tip="If you were ever given an award, please provide the details. If not, then leave these fields blank."   title="Name of Award"  value=''   />
						<?php if(isset($NIUMBA_awards1Name) && $NIUMBA_awards1Name!=""){ ?>
						  <script>
						      document.getElementById("NIUMBA_awards1Name").value = "<?php echo str_replace("\n", '\n', $NIUMBA_awards1Name );  ?>";
						      document.getElementById("NIUMBA_awards1Name").style.color = "";
						  </script>
						<?php } ?>
						
						<div style='display:none'><div class='errorMsg' id= 'NIUMBA_awards1Name_error'></div></div>
						</div>
					</td>
					<td>
						<div class='fieldBoxLarge'>
						<input type='text' name='NIUMBA_awards1Year' id='NIUMBA_awards1Year'  validate="validateInteger"    caption="details"   minlength="4"   maxlength="4"     tip="If you were ever given an award, please provide the details. If not, then leave these fields blank."   title="Year of Award"  value=''   />
						<?php if(isset($NIUMBA_awards1Year) && $NIUMBA_awards1Year!=""){ ?>
						  <script>
						      document.getElementById("NIUMBA_awards1Year").value = "<?php echo str_replace("\n", '\n', $NIUMBA_awards1Year );  ?>";
						      document.getElementById("NIUMBA_awards1Year").style.color = "";
						  </script>
						<?php } ?>
						
						<div style='display:none'><div class='errorMsg' id= 'NIUMBA_awards1Year_error'></div></div>
						</div>
					</td>
					<td>
						<div class='fieldBoxLarge'>
						<input type='text' name='NIUMBA_awards1For' id='NIUMBA_awards1For'  validate="validateStr"    caption="details"   minlength="2"   maxlength="100"     tip="If you were ever given an award, please provide the details. If not, then leave these fields blank."   title="For"  value=''   />
						<?php if(isset($NIUMBA_awards1For) && $NIUMBA_awards1For!=""){ ?>
						  <script>
						      document.getElementById("NIUMBA_awards1For").value = "<?php echo str_replace("\n", '\n', $NIUMBA_awards1For );  ?>";
						      document.getElementById("NIUMBA_awards1For").style.color = "";
						  </script>
						<?php } ?>
						
						<div style='display:none'><div class='errorMsg' id= 'NIUMBA_awards1For_error'></div></div>
						</div>
					</td>
				</tr>

				<tr class="cutom-tr">
					<td>
						Second
					</td>
					<td>
						<div class='fieldBoxLarge'>
						<input type='text' name='NIUMBA_awards2Name' id='NIUMBA_awards2Name'  validate="validateStr"    caption="details"   minlength="2"   maxlength="100"     tip="If you were ever given an award, please provide the details. If not, then leave these fields blank."   title="Name of Award"  value=''   />
						<?php if(isset($NIUMBA_awards2Name) && $NIUMBA_awards2Name!=""){ ?>
						  <script>
						      document.getElementById("NIUMBA_awards2Name").value = "<?php echo str_replace("\n", '\n', $NIUMBA_awards2Name );  ?>";
						      document.getElementById("NIUMBA_awards2Name").style.color = "";
						  </script>
						<?php } ?>
						
						<div style='display:none'><div class='errorMsg' id= 'NIUMBA_awards2Name_error'></div></div>
						</div>
					</td>
					<td>
						<div class='fieldBoxLarge'>
						<input type='text' name='NIUMBA_awards2Year' id='NIUMBA_awards2Year'  validate="validateInteger"    caption="details"   minlength="4"   maxlength="4"     tip="If you were ever given an award, please provide the details. If not, then leave these fields blank."   title="Year of Award"  value=''   />
						<?php if(isset($NIUMBA_awards2Year) && $NIUMBA_awards2Year!=""){ ?>
						  <script>
						      document.getElementById("NIUMBA_awards2Year").value = "<?php echo str_replace("\n", '\n', $NIUMBA_awards2Year );  ?>";
						      document.getElementById("NIUMBA_awards2Year").style.color = "";
						  </script>
						<?php } ?>
						
						<div style='display:none'><div class='errorMsg' id= 'NIUMBA_awards2Year_error'></div></div>
						</div>
					</td>
					<td>
						<div class='fieldBoxLarge'>
						<input type='text' name='NIUMBA_awards2For' id='NIUMBA_awards2For'  validate="validateStr"    caption="details"   minlength="2"   maxlength="100"     tip="If you were ever given an award, please provide the details. If not, then leave these fields blank."   title="For"  value=''   />
						<?php if(isset($NIUMBA_awards2For) && $NIUMBA_awards2For!=""){ ?>
						  <script>
						      document.getElementById("NIUMBA_awards2For").value = "<?php echo str_replace("\n", '\n', $NIUMBA_awards2For );  ?>";
						      document.getElementById("NIUMBA_awards2For").style.color = "";
						  </script>
						<?php } ?>
						
						<div style='display:none'><div class='errorMsg' id= 'NIUMBA_awards2For_error'></div></div>
						</div>
					</td>
				</tr>
			</table>


			<table width="100%" style="margin-top:10px;margin-bottom:15px; border:1px solid #000" >
				<tr class="cutom-tr">
					<td width='120px'>
						<div class="fieldBoxLarge"></div>
					</td>
					<td>
						Name of Activity
					</td>
					<td>
						Period of Participation
					</td>
					<td>
						Position
					</td>
				</tr>
				<tr class="cutom-tr">
					<td width='120px'>
					First
					</td>
					<td>
						<div class='fieldBoxLarge'>
						<input type='text' name='NIUMBA_activity1Name' id='NIUMBA_activity1Name'  validate="validateStr"    caption="details"   minlength="2"   maxlength="100"     tip="If you were involved in any extra-curricular activity during your acedemic years, mention the details here. If this does not apply to you, leve these fields blank."   title="Name of activity"  value=''   />
						<?php if(isset($NIUMBA_activity1Name) && $NIUMBA_activity1Name!=""){ ?>
						  <script>
						      document.getElementById("NIUMBA_activity1Name").value = "<?php echo str_replace("\n", '\n', $NIUMBA_activity1Name );  ?>";
						      document.getElementById("NIUMBA_activity1Name").style.color = "";
						  </script>
						<?php } ?>
						
						<div style='display:none'><div class='errorMsg' id= 'NIUMBA_activity1Name_error'></div></div>
						</div>
					</td>

					<td>
						<div class='fieldBoxLarge'>
						<input type='text' name='NIUMBA_activity1Period' id='NIUMBA_activity1Period'  validate="validateStr"    caption="details"   minlength="2"   maxlength="100"     tip="If you were involved in any extra-curricular activity during your acedemic years, mention the details here. If this does not apply to you, leve these fields blank."   title="Period of activity"  value=''   />
						<?php if(isset($NIUMBA_activity1Period) && $NIUMBA_activity1Period!=""){ ?>
						  <script>
						      document.getElementById("NIUMBA_activity1Period").value = "<?php echo str_replace("\n", '\n', $NIUMBA_activity1Period );  ?>";
						      document.getElementById("NIUMBA_activity1Period").style.color = "";
						  </script>
						<?php } ?>
						
						<div style='display:none'><div class='errorMsg' id= 'NIUMBA_activity1Period_error'></div></div>
						</div>
					</td>

					<td>
						<div class='fieldBoxLarge'>
						<input type='text' name='NIUMBA_activity1Position' id='NIUMBA_activity1Position'  validate="validateStr"    caption="details"   minlength="2"   maxlength="100"     tip="If you were involved in any extra-curricular activity during your acedemic years, mention the details here. If this does not apply to you, leve these fields blank."   title="Name of position"  value=''   />
						<?php if(isset($NIUMBA_activity1Position) && $NIUMBA_activity1Position!=""){ ?>
						  <script>
						      document.getElementById("NIUMBA_activity1Position").value = "<?php echo str_replace("\n", '\n', $NIUMBA_activity1Position );  ?>";
						      document.getElementById("NIUMBA_activity1Position").style.color = "";
						  </script>
						<?php } ?>
						
						<div style='display:none'><div class='errorMsg' id= 'NIUMBA_activity1Position_error'></div></div>
						</div>
					</td>
				</tr>
				<tr class="cutom-tr">
					<td width='120px'>
					Second
					</td>
					<td>
						<div class='fieldBoxLarge'>
						<input type='text' name='NIUMBA_activity2Name' id='NIUMBA_activity2Name'  validate="validateStr"    caption="details"   minlength="2"   maxlength="100"     tip="If you were involved in any extra-curricular activity during your acedemic years, mention the details here. If this does not apply to you, leve these fields blank."   title="Name of activity"  value=''   />
						<?php if(isset($NIUMBA_activity2Name) && $NIUMBA_activity2Name!=""){ ?>
						  <script>
						      document.getElementById("NIUMBA_activity2Name").value = "<?php echo str_replace("\n", '\n', $NIUMBA_activity2Name );  ?>";
						      document.getElementById("NIUMBA_activity2Name").style.color = "";
						  </script>
						<?php } ?>
						
						<div style='display:none'><div class='errorMsg' id= 'NIUMBA_activity2Name_error'></div></div>
						</div>
					</td>

					<td>
						<div class='fieldBoxLarge'>
						<input type='text' name='NIUMBA_activity2Period' id='NIUMBA_activity2Period'  validate="validateStr"    caption="details"   minlength="2"   maxlength="100"     tip="If you were involved in any extra-curricular activity during your acedemic years, mention the details here. If this does not apply to you, leve these fields blank."   title="Period of activity"  value=''   />
						<?php if(isset($NIUMBA_activity2Period) && $NIUMBA_activity2Period!=""){ ?>
						  <script>
						      document.getElementById("NIUMBA_activity2Period").value = "<?php echo str_replace("\n", '\n', $NIUMBA_activity2Period );  ?>";
						      document.getElementById("NIUMBA_activity2Period").style.color = "";
						  </script>
						<?php } ?>
						
						<div style='display:none'><div class='errorMsg' id= 'NIUMBA_activity2Period_error'></div></div>
						</div>
					</td>

					<td>
						<div class='fieldBoxLarge'>
						<input type='text' name='NIUMBA_activity2Position' id='NIUMBA_activity2Position'  validate="validateStr"    caption="details"   minlength="2"   maxlength="100"     tip="If you were involved in any extra-curricular activity during your acedemic years, mention the details here. If this does not apply to you, leve these fields blank."   title="Name of position"  value=''   />
						<?php if(isset($NIUMBA_activity2Position) && $NIUMBA_activity2Position!=""){ ?>
						  <script>
						      document.getElementById("NIUMBA_activity2Position").value = "<?php echo str_replace("\n", '\n', $NIUMBA_activity2Position );  ?>";
						      document.getElementById("NIUMBA_activity2Position").style.color = "";
						  </script>
						<?php } ?>
						
						<div style='display:none'><div class='errorMsg' id= 'NIUMBA_activity2Position_error'></div></div>
						</div>
					</td>
				</tr>
			</table>
			
			<li>
				<h3 class="upperCase">Statement of purpose</h3>
				</li>
				<li>
				<div class='additionalInfoLeftCol'>
				<label>What are your personal and professional goals and how will the course applied for by you at Noida International University help you to accomplish the same?: </label>
				<div class='fieldBoxLarge'>
				<textarea style='width: 624px;' name='NIUMBA_personalGoals' id='NIUMBA_personalGoals'  validate="validateStr"   required="true"   caption="personal goals"   minlength="2"   maxlength="1000"     tip="Please write a short essay on what are your personal and professional goals and how will the course applied for by you at Noida International University help you to accomplish the same?"   title="What are your personal and professional goals and how will the course applied for by you at Noida International University help you to accomplish the same?"   ></textarea>
				<?php if(isset($NIUMBA_personalGoals) && $NIUMBA_personalGoals!=""){ ?>
				  <script>
				      document.getElementById("NIUMBA_personalGoals").value = "<?php echo str_replace("\n", '\n', $NIUMBA_personalGoals );  ?>";
				      document.getElementById("NIUMBA_personalGoals").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIUMBA_personalGoals_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Name and describe your two biggest strengths and weaknesses.: </label>
				<div class='fieldBoxLarge'>
				<textarea style='width: 624px;' name='NIUMBA_strengths' id='NIUMBA_strengths'  validate="validateStr"   required="true"   caption="strengths and weaknesses"   minlength="2"   maxlength="1000"     tip="Write a short essay describing 2 of your biggest strengths and 2 weaknesses."   title="Name and describe your two biggest strengths and weaknesses."   ></textarea>
				<?php if(isset($NIUMBA_strengths) && $NIUMBA_strengths!=""){ ?>
				  <script>
				      document.getElementById("NIUMBA_strengths").value = "<?php echo str_replace("\n", '\n', $NIUMBA_strengths );  ?>";
				      document.getElementById("NIUMBA_strengths").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIUMBA_strengths_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>How do you intend to engage, enrich and evolve the core culture of NIU?: </label>
				<div class='fieldBoxLarge'>
				<textarea style='width: 624px;' name='NIUMBA_culture' id='NIUMBA_culture'  validate="validateStr"   required="true"   caption="details"   minlength="2"   maxlength="1000"     tip="Write a short essay on how do you intend to engage, enrich and evolve the core culture of NIU?"   title="How do you intend to engage, enrich and evolve the core culture of NIU?"   ></textarea>
				<?php if(isset($NIUMBA_culture) && $NIUMBA_culture!=""){ ?>
				  <script>
				      document.getElementById("NIUMBA_culture").value = "<?php echo str_replace("\n", '\n', $NIUMBA_culture );  ?>";
				      document.getElementById("NIUMBA_culture").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIUMBA_culture_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>What does "Revolutionizing Learning Experience" mean to you?: </label>
				<div class='fieldBoxLarge'>
				<textarea style='width: 624px;' name='NIUMBA_experience' id='NIUMBA_experience'  validate="validateStr"   required="true"   caption="details"   minlength="2"   maxlength="1000"     tip='Please write a short essay on what does "Revolutionizing Learning Experience" mean to you?'   title='What does "Revolutionizing Learning Experience" mean to you?'   ></textarea>
				<?php if(isset($NIUMBA_experience) && $NIUMBA_experience!=""){ ?>
				  <script>
				      document.getElementById("NIUMBA_experience").value = "<?php echo str_replace("\n", '\n', $NIUMBA_experience );  ?>";
				      document.getElementById("NIUMBA_experience").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIUMBA_experience_error'></div></div>
				</div>
				</div>
			</li>

			<?php if(is_array($gdpiLocations) && count($gdpiLocations)): ?>
			<li>
				<h3 class=upperCase'>GD/PI Location</h3>
				<label style='font-weight:normal'>Preferred GD/PI location: </label>
				<div class='fieldBoxLarge'>
				<select name='preferredGDPILocation' id='preferredGDPILocation' onmouseover="showTipOnline('Select your preferred GD/PI location.',this);" onmouseout='hidetip();'  validate="validateSelect"  minlength="1"   maxlength="1500"  required="true" caption="Preferred GD/PI location">
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
			<?php endif; ?>
	
			<li>
				<h3 class="upperCase">Disclaimer</h3>
				<label style="font-weight:normal; padding-top:0">Declaration:</label>
				<div class='fieldBoxLarge' style="width:620px; color:#666666; font-style:italic">
1. I have read and understood the full requirements of the course, eligibility criteria, terms and conditions and
other important information as indicated in the Prospectus/website.<br/>
2. I confirm that the information furnished by me in this Application Form is true to the best of my knowledge. I
understand that any false or misleading statement given by me may lead to the cancellation of admission or
expulsion fromthe course at any stage.<br/>
3. I undertake to abide by the rules and regulations of the NIU School of Business Management as
prescribed from time to time. If I violate at any point of time any of the stipulated rules and regulations, then the
University is free to initiate appropriate disciplinary action against me.
				</div>
				<div class='additionalInfoLeftCol'>
				<label>I agree to the terms stated above: </label>
				<div class='fieldBoxLarge'>
				<input type='checkbox'   validate="validateChecked" checked  required="true"   name='NIUMBA_agreeToTerms[]' id='NIUMBA_agreeToTerms0'   value=''    title="I agree to the terms stated above"   onmouseover="showTipOnline('Please check to accept terms',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please check to accept terms',this);" onmouseout="hidetip();" ></span>&nbsp;&nbsp;
				<?php if(isset($NIUMBA_agreeToTerms) && $NIUMBA_agreeToTerms!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["NIUMBA_agreeToTerms[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$NIUMBA_agreeToTerms);
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
				
				<div style='display:none'><div class='errorMsg' id= 'NIUMBA_agreeToTerms0_error'></div></div>
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
  
<?php
      if( isset($NIUMBA_Nationality) && $NIUMBA_Nationality!="INDIAN" ){
	      echo "<script>checkPassportDetails();</script>";
      }else if( isset($NIUMBA_nationalityChild) && $NIUMBA_nationalityChild!="INDIAN" ){
	      echo "<script>checkPassportDetails();</script>";
      }
?>
  
<script>
function toggleExamDetails(id,num){
	
	cb = $('courseNIUMBA'+num);
	if(cb.checked) {
		if($(id+'Details')) {
			$(id+'Details').style.display = '';
			$(id+'Details1').style.display = '';
			if(id=='cat' || id=='mat' || id=='gmat' || id=='xat' || id=='atma'){
				$(id+'RollNumberAdditional').setAttribute('required','true');
				$(id+'PercentileAdditional').setAttribute('required','true');
				$(id+'DateOfExaminationAdditional').setAttribute('required','true');
				$(id+'ScoreAdditional').setAttribute('required','true');
			}else if(id=='cmat'){
				$(id+'RollNumberAdditional').setAttribute('required','true');
				$(id+'PercentileAdditionalNIUMBA').setAttribute('required','true');
				$(id+'DateOfExaminationAdditional').setAttribute('required','true');
				$(id+'ScoreAdditional').setAttribute('required','true');
			}
		}
	}
	else {
		if($(id+'Details')) {
				$(id+'Details').style.display = 'none';
				$(id+'Details1').style.display = 'none';
		}		
		if(id=='cat' || id=='mat' || id=='gmat' || id=='xat' || id=='atma'){		
				if($(id+'DateOfExaminationAdditional')) {
					$(id+'DateOfExaminationAdditional').value = '';
				}
				if($(id+'RollNumberAdditional')) {
					$(id+'RollNumberAdditional').value = '';
				}
				if($(id+'PercentileAdditional')) {
					$(id+'PercentileAdditional').value = '';
				}
				if($(id+'ScoreAdditional')) {
					$(id+'ScoreAdditional').value = '';
				}
				$(id+'RollNumberAdditional').removeAttribute('required');
				$(id+'PercentileAdditional').removeAttribute('required');
				$(id+'DateOfExaminationAdditional').removeAttribute('required');
				$(id+'ScoreAdditional').removeAttribute('required');
		}else if(id=='cmat'){
				if($(id+'DateOfExaminationAdditional')) {
					$(id+'DateOfExaminationAdditional').value = '';
				}
				if($(id+'RollNumberAdditional')) {
					$(id+'RollNumberAdditional').value = '';
				}
				if($(id+'PercentileAdditionalNIUMBA')) {
					$(id+'PercentileAdditionalNIUMBA').value = '';
				}
				if($(id+'ScoreAdditional')) {
					$(id+'ScoreAdditional').value = '';
				}
				$(id+'RollNumberAdditional').removeAttribute('required');
				$(id+'PercentileAdditionalNIUMBA').removeAttribute('required');
				$(id+'DateOfExaminationAdditional').removeAttribute('required');
				$(id+'ScoreAdditional').removeAttribute('required');
		}
	}
}
  </script>

<?php
if( (isset($catDateOfExaminationAdditional) && $catDateOfExaminationAdditional!='')  || (isset($catPercentileAdditional) && $catPercentileAdditional!="") || (isset($catRollNumberAdditional) && $catRollNumberAdditional!="")){
      echo "<script>document.getElementById('courseNIUMBA0').checked = true; toggleExamDetails('cat','0');</script>";
}
if( (isset($matDateOfExaminationAdditional) && $matDateOfExaminationAdditional!='')  || (isset($matPercentileAdditional) && $matPercentileAdditional!="") || (isset($matRollNumberAdditional) && $matRollNumberAdditional!="")){
      echo "<script>document.getElementById('courseNIUMBA1').checked = true; toggleExamDetails('mat','1');</script>";
}
if( (isset($xatDateOfExaminationAdditional) && $xatDateOfExaminationAdditional!='')  || (isset($xatPercentileAdditional) && $xatPercentileAdditional!="") || (isset($xatRollNumberAdditional) && $xatRollNumberAdditional!="")){
      echo "<script>document.getElementById('courseNIUMBA2').checked = true; toggleExamDetails('xat','2');</script>";
}
if( (isset($atmaDateOfExaminationAdditional) && $atmaDateOfExaminationAdditional!='')  || (isset($atmaPercentileAdditional) && $atmaPercentileAdditional!="") || (isset($atmaRollNumberAdditional) && $atmaRollNumberAdditional!="")){
      echo "<script>document.getElementById('courseNIUMBA3').checked = true; toggleExamDetails('atma','3');</script>";
}
if( (isset($gmatDateOfExaminationAdditional) && $gmatDateOfExaminationAdditional!='')  || (isset($gmatPercentileAdditional) && $gmatPercentileAdditional!="") || (isset($gmatRollNumberAdditional) && $gmatRollNumberAdditional!="")){
      echo "<script>document.getElementById('courseNIUMBA4').checked = true; toggleExamDetails('gmat','4');</script>";
}
?>