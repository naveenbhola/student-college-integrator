<style>
.cutom-tr {height: 50px;}
</style>
<script>
function changeAilmentDetails(obj){
	if(obj.value=='Yes'){	//Enable Details
		$("NIU_AilmentDetails").disabled = false;
		$("NIU_AilmentDetails").setAttribute('required','true');
		$("NIU_AilmentDetails").value = "";
		$("NIU_AilmentDetails").style.color = "";
	}
	else{
		$("NIU_AilmentDetails").disabled = true;
		$("NIU_AilmentDetails").removeAttribute('required');
		$("NIU_AilmentDetails").value = "";
	}
	$('NIU_AilmentDetails_error').innerHTML = '';
	$('NIU_AilmentDetails_error').parentNode.style.display = 'none';	
}

function changeProbationDetails(obj){
	if(obj.value=='Yes'){	//Enable Details
		$("NIU_AcademicProbationDetails").disabled = false;
		$("NIU_AcademicProbationDetails").setAttribute('required','true');
		$("NIU_AcademicProbationDetails").value = "";
		$("NIU_AcademicProbationDetails").style.color = "";
	}
	else{
		$("NIU_AcademicProbationDetails").disabled = true;
		$("NIU_AcademicProbationDetails").removeAttribute('required');
		$("NIU_AcademicProbationDetails").value = "";
	}
	$('NIU_AcademicProbationDetails_error').innerHTML = '';
	$('NIU_AcademicProbationDetails_error').parentNode.style.display = 'none';	
}

function checkPassportDetails(){
	$('passportDetails').style.display='';
	$("NIU_passportNumber").setAttribute('required','true');
	$("NIU_passportIssueDate").setAttribute('required','true');
	$("NIU_passportTillDate").setAttribute('required','true');
	$("NIU_issuingCountry").setAttribute('required','true');
	$("NIU_visaType").setAttribute('required','true');
	$("NIU_timePeriodYear").setAttribute('required','true');
	$("NIU_timePeriodMonth").setAttribute('required','true');
	$("NIU_ExamTaken0").setAttribute('validate','validateCheckedGroup');
	$("NIU_ExamTaken1").setAttribute('validate','validateCheckedGroup');
	$("NIU_ExamTaken2").setAttribute('validate','validateCheckedGroup');
}

function checkExamTaken(obj){
	var examValue = obj.value;
	if($(examValue+'Details').style.display == ''){
		$(examValue+'Details').style.display = 'none';
		if(examValue=='TOEFL'){
			$("NIU_ExamRollNumber").removeAttribute('required');
			$("NIU_ExamScore").removeAttribute('required');
			$('NIU_ExamRollNumber_error').innerHTML = '';
			$('NIU_ExamRollNumber_error').parentNode.style.display = 'none';	
			$('NIU_ExamScore_error').innerHTML = '';
			$('NIU_ExamScore_error').parentNode.style.display = 'none';	
			$("NIU_ExamRollNumber").value = '';
			$("NIU_ExamScore").value = '';
		}
		else{
			$("NIU_ExamRollNumber"+examValue).removeAttribute('required');
			$("NIU_ExamScore"+examValue).removeAttribute('required');			
			$('NIU_ExamRollNumber'+examValue+'_error').innerHTML = '';
			$('NIU_ExamRollNumber'+examValue+'_error').parentNode.style.display = 'none';	
			$('NIU_ExamScore'+examValue+'_error').innerHTML = '';
			$('NIU_ExamScore'+examValue+'_error').parentNode.style.display = 'none';	
			$("NIU_ExamRollNumber"+examValue).value = '';
			$("NIU_ExamScore"+examValue).value = '';
		}		
	}
	else{
		$(examValue+'Details').style.display = '';
		if(examValue=='TOEFL'){
			$("NIU_ExamRollNumber").setAttribute('required','true');
			$('NIU_ExamRollNumber_error').innerHTML = '';
			$('NIU_ExamRollNumber_error').parentNode.style.display = 'none';	
			$("NIU_ExamScore").setAttribute('required','true');
			$('NIU_ExamScore_error').innerHTML = '';
			$('NIU_ExamScore_error').parentNode.style.display = 'none';	
		}
		else{
			$("NIU_ExamRollNumber"+examValue).setAttribute('required','true');
			$("NIU_ExamScore"+examValue).setAttribute('required','true');			
			$('NIU_ExamRollNumber'+examValue+'_error').innerHTML = '';
			$('NIU_ExamRollNumber'+examValue+'_error').parentNode.style.display = 'none';	
			$('NIU_ExamScore'+examValue+'_error').innerHTML = '';
			$('NIU_ExamScore'+examValue+'_error').parentNode.style.display = 'none';	
		}		
	}
}
</script>
<div class='formChildWrapper'>
	<div class='formSection'>
		<ul>

<?php if($action != 'updateScore'):?>
	
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
				<h3 class="upperCase">Additional Personal Information</h3>

				<div class='additionalInfoLeftCol' style='width:620px;'>

					<label>Height Ft.: </label>
					
					<div class='fieldBoxLarge' style="width:300px;">
						<input style='width:50px;' type='text' name='NIU_HeightFeet' id='NIU_HeightFeet'  validate="validateInteger"   required="true"   caption="height in feet"   minlength="1"   maxlength="1"     tip="Enter your height in feet and inches. For example, if your height is 5 feel 6 inches, enter 5 in the box against ft. and 6 in the box against in."   title="Height"  value=''   />
						<?php if(isset($NIU_HeightFeet) && $NIU_HeightFeet!=""){ ?>
						  <script>
						      document.getElementById("NIU_HeightFeet").value = "<?php echo str_replace("\n", '\n', $NIU_HeightFeet );  ?>";
						      document.getElementById("NIU_HeightFeet").style.color = "";
						  </script>
						<?php } ?>
		
						&nbsp;in.:
						<input style='width:50px;' type='text' name='NIU_HeightInch' id='NIU_HeightInch'  validate="validateInteger"   required="true"   caption="height in inches"   minlength="1"   maxlength="2"     tip="Enter your height in feet and inches. For example, if your height is 5 feel 6 inches, enter 5 in the box against ft. and 6 in the box against in."   title="Height"  value=''   />
						<?php if(isset($NIU_HeightInch) && $NIU_HeightInch!=""){ ?>
						  <script>
						      document.getElementById("NIU_HeightInch").value = "<?php echo str_replace("\n", '\n', $NIU_HeightInch );  ?>";
						      document.getElementById("NIU_HeightInch").style.color = "";
						  </script>
						<?php } ?>
						<div style='display:none;'><div class='errorMsg' id= 'NIU_HeightFeet_error'></div></div>
						<div style='display:none;'><div class='errorMsg' id= 'NIU_HeightInch_error'></div></div>
					</div>
				</div>
				

			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Category: </label>
				<div class='fieldBoxLarge'>
				<select style='width:150px;' name='NIU_Category' id='NIU_Category'    tip="Please select the appropriate category that applies to you."   title="Category"    required="true"  validate='validateSelect' caption='category' minlength='1' maxlength='50' onmouseover="showTipOnline('Please select the appropriate category that applies to you.',this);" onmouseout="hidetip();" >
				<option value='' selected>Select</option><option value='GEN' >Gen</option><option value='SC' >SC</option><option value='ST' >ST</option><option value='OBC' >OBC</option></select>
				<?php if(isset($NIU_Category) && $NIU_Category!=""){ ?>
			      <script>
				  var selObj = document.getElementById("NIU_Category"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $NIU_Category;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIU_Category_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol' style="width: 800px;">
				<label>Admission category: </label>
				<div class='fieldBoxLarge' style="width:420px;">
				<input type='radio'   required="true"   name='NIU_AdmissionCategory' id='NIU_AdmissionCategory0'   value='NON-SPONSORED'  checked  title="Admission category"   onmouseover="showTipOnline('Please select a category under which you would want to be considered for admission.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select a category under which you would want to be considered for admission.',this);" onmouseout="hidetip();" >Non-Sponsored</span>&nbsp;&nbsp;
				<input type='radio'   required="true"   name='NIU_AdmissionCategory' id='NIU_AdmissionCategory1'   value='SPONSORED'    title="Admission category"   onmouseover="showTipOnline('Please select a category under which you would want to be considered for admission.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select a category under which you would want to be considered for admission.',this);" onmouseout="hidetip();" >Sponsored</span>&nbsp;&nbsp;
				<input type='radio'   required="true"   name='NIU_AdmissionCategory' id='NIU_AdmissionCategory2'   value='COMPANY SPONSORED'    title="Admission category"   onmouseover="showTipOnline('Please select a category under which you would want to be considered for admission.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select a category under which you would want to be considered for admission.',this);" onmouseout="hidetip();" >Company Sponsored</span>&nbsp;&nbsp;
				<input type='radio'   required="true"   name='NIU_AdmissionCategory' id='NIU_AdmissionCategory3'   value='NRI'    title="Admission category"   onmouseover="showTipOnline('Please select a category under which you would want to be considered for admission.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select a category under which you would want to be considered for admission.',this);" onmouseout="hidetip();" >NRI</span>&nbsp;&nbsp;
				<input type='radio'   required="true"   name='NIU_AdmissionCategory' id='NIU_AdmissionCategory4'   value='FOREIGN'    title="Admission category"   onmouseover="showTipOnline('Please select a category under which you would want to be considered for admission.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select a category under which you would want to be considered for admission.',this);" onmouseout="hidetip();" >Foreign</span>&nbsp;&nbsp;
				<?php if(isset($NIU_AdmissionCategory) && $NIU_AdmissionCategory!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["NIU_AdmissionCategory"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $NIU_AdmissionCategory;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIU_AdmissionCategory_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Do you suffer from any chronic ailment? If yes give details: </label>
				<div class='fieldBoxLarge'>
				<input type='radio'  onClick="changeAilmentDetails(this);" required="true"   name='NIU_Ailment' id='NIU_Ailment0'   value='Yes'    title="Ailment"   onmouseover="showTipOnline('Do you suffer from any chronic ailment? If yes give details',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Do you suffer from any chronic ailment? If yes give details',this);" onmouseout="hidetip();" >Yes</span>&nbsp;&nbsp;
				<input type='radio'  onClick="changeAilmentDetails(this);" required="true"   name='NIU_Ailment' id='NIU_Ailment1'   value='No'  checked  title="Ailment"   onmouseover="showTipOnline('Do you suffer from any chronic ailment? If yes give details',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Do you suffer from any chronic ailment? If yes give details',this);" onmouseout="hidetip();" >No</span>&nbsp;&nbsp;
				<?php if(isset($NIU_Ailment) && $NIU_Ailment!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["NIU_Ailment"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $NIU_Ailment;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIU_Ailment_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Details: </label>
				<div class='fieldBoxLarge'>
				<input disabled type='text' name='NIU_AilmentDetails' id='NIU_AilmentDetails'  validate="validateStr"    caption="details"   minlength="2"   maxlength="50"      value=''   />
				<?php if(isset($NIU_AilmentDetails) && $NIU_AilmentDetails!=""){ ?>
				  <script>
				      document.getElementById("NIU_AilmentDetails").value = "<?php echo str_replace("\n", '\n', $NIU_AilmentDetails );  ?>";
				      document.getElementById("NIU_AilmentDetails").style.color = "";
					document.getElementById("NIU_AilmentDetails").disabled = false;
					document.getElementById("NIU_AilmentDetails").setAttribute('required','true');				      
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIU_AilmentDetails_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Have you ever been dismissed or put on academic probation? If yes give details.: </label>
				<div class='fieldBoxLarge'>
				<input type='radio' onClick="changeProbationDetails(this);"  required="true"   name='NIU_AcademicProbation' id='NIU_AcademicProbation0'   value='Yes'    title="Probation"   onmouseover="showTipOnline('Have you ever been dismissed or put on academic probation? If yes give details.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Have you ever been dismissed or put on academic probation? If yes give details.',this);" onmouseout="hidetip();" >Yes</span>&nbsp;&nbsp;
				<input type='radio' onClick="changeProbationDetails(this);"  required="true"   name='NIU_AcademicProbation' id='NIU_AcademicProbation1'   value='No'  checked  title="Probation"   onmouseover="showTipOnline('Have you ever been dismissed or put on academic probation? If yes give details.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Have you ever been dismissed or put on academic probation? If yes give details.',this);" onmouseout="hidetip();" >No</span>&nbsp;&nbsp;
				<?php if(isset($NIU_AcademicProbation) && $NIU_AcademicProbation!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["NIU_AcademicProbation"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $NIU_AcademicProbation;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIU_AcademicProbation_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Details: </label>
				<div class='fieldBoxLarge'>
				<input disabled type='text' name='NIU_AcademicProbationDetails' id='NIU_AcademicProbationDetails'  validate="validateStr"    caption="details"   minlength="2"   maxlength="50"      value=''   />
				<?php if(isset($NIU_AcademicProbationDetails) && $NIU_AcademicProbationDetails!=""){ ?>
				  <script>
				      document.getElementById("NIU_AcademicProbationDetails").value = "<?php echo str_replace("\n", '\n', $NIU_AcademicProbationDetails );  ?>";
				      document.getElementById("NIU_AcademicProbationDetails").style.color = "";
					document.getElementById("NIU_AcademicProbationDetails").disabled = false;
					document.getElementById("NIU_AcademicProbationDetails").setAttribute('required','true');				      
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIU_AcademicProbationDetails_error'></div></div>
				</div>
				</div>
			</li>

			<input type='hidden' id='NIU_nationalityChild' name='NIU_nationalityChild' value='<?=$NIU_nationalityChild?>'>
			<input type='hidden' name='NIU_Nationality' id='NIU_Nationality' value='<?=$NIU_Nationality?>'>
			<li>
				<div class='additionalInfoLeftCol'>
				<label>Nationality: </label>
				<div class='fieldBoxLarge'>
				<input disabled type='radio'   required="true"   name='NIU_NationalityField' id='NIU_Nationality0'   value='INDIAN'  title="Nationality"   onmouseover="showTipOnline('Please select your nationality.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select your nationality.',this);" onmouseout="hidetip();" >Indian</span>&nbsp;&nbsp;
				<input disabled type='radio'   required="true"   name='NIU_NationalityField' id='NIU_Nationality1'   value='OTHER'  title="Nationality"   onmouseover="showTipOnline('Please select your nationality.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select your nationality.',this);" onmouseout="hidetip();" >Other</span>&nbsp;&nbsp;
				<?php if(isset($NIU_Nationality) && $NIU_Nationality!=""){ ?>
				  <script>
				        if("<?php echo $NIU_Nationality;?>"=="INDIAN"){
						document.getElementById('NIU_Nationality0').checked = true;
				        }
					else{
						document.getElementById('NIU_Nationality1').checked = true;
					}
				  </script>
				<?php }else if(isset($NIU_nationalityChild) && $NIU_nationalityChild!=""){ ?>
				  <script>
				        if("<?php echo $NIU_nationalityChild;?>"=="INDIAN"){
						document.getElementById('NIU_Nationality0').checked = true;
						document.getElementById('NIU_Nationality').value = 'INDIAN';
				        }
					else{
						document.getElementById('NIU_Nationality1').checked = true;
						document.getElementById('NIU_Nationality').value = 'OTHER';
					}
				  </script>
				<?php } ?>				
				<div style='display:none'><div class='errorMsg' id= 'NIU_Nationality_error'></div></div>
				</div>
				</div>
			</li>

			<div id='passportDetails' style='display:none;'>
			<li>
				<div class='additionalInfoLeftCol'>
				<label>Passport Number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='NIU_passportNumber' id='NIU_passportNumber'  validate="validateStr"    caption="passport number"   minlength="2"   maxlength="20"     tip="Please enter your passport number here. "   title="Passport Number"  value=''   />
				<?php if(isset($NIU_passportNumber) && $NIU_passportNumber!=""){ ?>
				  <script>
				      document.getElementById("NIU_passportNumber").value = "<?php echo str_replace("\n", '\n', $NIU_passportNumber );  ?>";
				      document.getElementById("NIU_passportNumber").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIU_passportNumber_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Issued Date: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='NIU_passportIssueDate' id='NIU_passportIssueDate' readonly minlength='1' maxlength='10'  validate="validateDateForms"   caption='date'          tip="Please enter the date of issue of your passport. If you are not sure about the date, refer your passport."   title="Issued Date"    onmouseover="showTipOnline('Please enter the date of issue of your passport. If you are not sure about the date, refer your passport.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('NIU_passportIssueDate'),'NIU_passportIssueDate_dateImg','dd/MM/yyyy');" />
				&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='NIU_passportIssueDate_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('NIU_passportIssueDate'),'NIU_passportIssueDate_dateImg','dd/MM/yyyy'); return false;" />
				<?php if(isset($NIU_passportIssueDate) && $NIU_passportIssueDate!=""){ ?>
				  <script>
				      document.getElementById("NIU_passportIssueDate").value = "<?php echo str_replace("\n", '\n', $NIU_passportIssueDate );  ?>";
				      document.getElementById("NIU_passportIssueDate").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIU_passportIssueDate_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Valid Till: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='NIU_passportTillDate' id='NIU_passportTillDate' readonly minlength='1' maxlength='10'  validate="validateDateForms" caption='date'        tip="Please enter the valid till date of  your passport. If you are not sure about the date, refer your passport."   title="Valid Till"    onmouseover="showTipOnline('Please enter the valid till date of  your passport. If you are not sure about the date, refer your passport.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('NIU_passportTillDate'),'NIU_passportTillDate_dateImg','dd/MM/yyyy');" />
				&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='NIU_passportTillDate_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('NIU_passportTillDate'),'NIU_passportTillDate_dateImg','dd/MM/yyyy'); return false;" />
				<?php if(isset($NIU_passportTillDate) && $NIU_passportTillDate!=""){ ?>
				  <script>
				      document.getElementById("NIU_passportTillDate").value = "<?php echo str_replace("\n", '\n', $NIU_passportTillDate );  ?>";
				      document.getElementById("NIU_passportTillDate").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIU_passportTillDate_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Issuing country: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='NIU_issuingCountry' id='NIU_issuingCountry'  validate="validateStr"    caption="country"   minlength="2"   maxlength="50"     tip="Please enter the country from where your passport was issued"   title="Issuing country"  value=''   />
				<?php if(isset($NIU_issuingCountry) && $NIU_issuingCountry!=""){ ?>
				  <script>
				      document.getElementById("NIU_issuingCountry").value = "<?php echo str_replace("\n", '\n', $NIU_issuingCountry );  ?>";
				      document.getElementById("NIU_issuingCountry").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIU_issuingCountry_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Visa Type: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='NIU_visaType' id='NIU_visaType'  validate="validateStr"    caption="visa type"   minlength="2"   maxlength="50"     tip="Please enter the type of visa you've been issued for your stay in India. If you are unsure about the type of vise, please refer your passport."   title="Visa Type"  value=''   />
				<?php if(isset($NIU_visaType) && $NIU_visaType!=""){ ?>
				  <script>
				      document.getElementById("NIU_visaType").value = "<?php echo str_replace("\n", '\n', $NIU_visaType );  ?>";
				      document.getElementById("NIU_visaType").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIU_visaType_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol' style="width:800px;">
				<label>Please enter the time period since when you've been living in India: </label>
				<div class='fieldBoxLarge' style="width:300px;">
				Year: 
				<input style="width:50px;" type='text' name='NIU_timePeriodYear' id='NIU_timePeriodYear'  validate="validateInteger"    caption="year"   minlength="1"   maxlength="2"     tip="Please enter the time period since when you've been living in india."   title="Please enter the time period since when you've been living in india."  value=''   />
				<?php if(isset($NIU_timePeriodYear) && $NIU_timePeriodYear!=""){ ?>
				  <script>
				      document.getElementById("NIU_timePeriodYear").value = "<?php echo str_replace("\n", '\n', $NIU_timePeriodYear );  ?>";
				      document.getElementById("NIU_timePeriodYear").style.color = "";
				  </script>
				<?php } ?>
				
				Month:
				<input style="width:50px;" type='text' name='NIU_timePeriodMonth' id='NIU_timePeriodMonth'  validate="validateInteger"    caption="month"   minlength="1"   maxlength="2"     tip="Please enter the time period since when you've been living in india."   title="Please enter the time period since when you've been living in india."  value=''   />
				<?php if(isset($NIU_timePeriodMonth) && $NIU_timePeriodMonth!=""){ ?>
				  <script>
				      document.getElementById("NIU_timePeriodMonth").value = "<?php echo str_replace("\n", '\n', $NIU_timePeriodMonth );  ?>";
				      document.getElementById("NIU_timePeriodMonth").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIU_timePeriodYear_error'></div></div>
				<div style='display:none'><div class='errorMsg' id= 'NIU_timePeriodMonth_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Select the exam that you've taken: </label>
				<div class='fieldBoxLarge'>
				<input type='checkbox'  onChange='checkExamTaken(this);'   caption="exam"   name='NIU_ExamTaken[]' id='NIU_ExamTaken0'   value='TOEFL'   title="Select the exam that you've taken"   onmouseover="showTipOnline('Please select the exam that you\'ve appeared for',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select the exam that you\'ve appeared for',this);" onmouseout="hidetip();" >Toefl</span>&nbsp;&nbsp;
				<input type='checkbox'  onChange='checkExamTaken(this);'    caption="exam"   name='NIU_ExamTaken[]' id='NIU_ExamTaken1'   value='IELTS'    title="Select the exam that you've taken"   onmouseover="showTipOnline('Please select the exam that you\'ve appeared for',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select the exam that you\'ve appeared for',this);" onmouseout="hidetip();" >IELTS</span>&nbsp;&nbsp;
				<input type='checkbox'  onChange='checkExamTaken(this);'    caption="exam"   name='NIU_ExamTaken[]' id='NIU_ExamTaken2'   value='SAT'    title="Select the exam that you've taken"   onmouseover="showTipOnline('Please select the exam that you\'ve appeared for',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select the exam that you\'ve appeared for',this);" onmouseout="hidetip();" >SAT</span>&nbsp;&nbsp;
				<?php if(isset($NIU_ExamTaken) && $NIU_ExamTaken!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["NIU_ExamTaken[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$NIU_ExamTaken);
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
				
				<div style='display:none'><div class='errorMsg' id= 'NIU_ExamTaken_error'></div></div>
				</div>
				</div>
			</li>

			<li id='TOEFLDetails' style='display: none;'>
				<div class='additionalInfoLeftCol'>
				<label>TOEFL Exam Roll Number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='NIU_ExamRollNumber' id='NIU_ExamRollNumber'  validate="validateStr"    caption="number"   minlength="2"   maxlength="20"     tip="Enter your exam roll number for the selected examination"   title="Exam Roll Number"  value=''   />
				<?php if(isset($NIU_ExamRollNumber) && $NIU_ExamRollNumber!=""){ ?>
				  <script>
				      document.getElementById("NIU_ExamRollNumber").value = "<?php echo str_replace("\n", '\n', $NIU_ExamRollNumber );  ?>";
				      document.getElementById("NIU_ExamRollNumber").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIU_ExamRollNumber_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>TOEFL Exam score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='NIU_ExamScore' id='NIU_ExamScore'  validate="validateFloat"    caption="score"   minlength="1"   maxlength="10"     tip="Enter your exam score for the selected examination"   title="Exam score"  value=''   />
				<?php if(isset($NIU_ExamScore) && $NIU_ExamScore!=""){ ?>
				  <script>
				      document.getElementById("NIU_ExamScore").value = "<?php echo str_replace("\n", '\n', $NIU_ExamScore );  ?>";
				      document.getElementById("NIU_ExamScore").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIU_ExamScore_error'></div></div>
				</div>
				</div>
			</li>
			<li id='IELTSDetails' style='display: none;'>
				<div class='additionalInfoLeftCol'>
				<label>IELTS Exam Roll Number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='NIU_ExamRollNumberIELTS' id='NIU_ExamRollNumberIELTS'  validate="validateStr"    caption="number"   minlength="2"   maxlength="20"     tip="Enter your exam roll number for the selected examination"   title="Exam Roll Number"  value=''   />
				<?php if(isset($NIU_ExamRollNumberIELTS) && $NIU_ExamRollNumberIELTS!=""){ ?>
				  <script>
				      document.getElementById("NIU_ExamRollNumberIELTS").value = "<?php echo str_replace("\n", '\n', $NIU_ExamRollNumberIELTS );  ?>";
				      document.getElementById("NIU_ExamRollNumberIELTS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIU_ExamRollNumberIELTS_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>IELTS Exam score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='NIU_ExamScoreIELTS' id='NIU_ExamScoreIELTS'  validate="validateFloat"    caption="score"   minlength="1"   maxlength="10"     tip="Enter your exam score for the selected examination"   title="Exam score"  value=''   />
				<?php if(isset($NIU_ExamScoreIELTS) && $NIU_ExamScoreIELTS!=""){ ?>
				  <script>
				      document.getElementById("NIU_ExamScoreIELTS").value = "<?php echo str_replace("\n", '\n', $NIU_ExamScoreIELTS );  ?>";
				      document.getElementById("NIU_ExamScoreIELTS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIU_ExamScoreIELTS_error'></div></div>
				</div>
				</div>
			</li>
			<li id='SATDetails' style='display: none;'>
				<div class='additionalInfoLeftCol'>
				<label>SAT Exam Roll Number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='NIU_ExamRollNumberSAT' id='NIU_ExamRollNumberSAT'  validate="validateStr"    caption="number"   minlength="2"   maxlength="20"     tip="Enter your exam roll number for the selected examination"   title="Exam Roll Number"  value=''   />
				<?php if(isset($NIU_ExamRollNumberSAT) && $NIU_ExamRollNumberSAT!=""){ ?>
				  <script>
				      document.getElementById("NIU_ExamRollNumberSAT").value = "<?php echo str_replace("\n", '\n', $NIU_ExamRollNumberSAT );  ?>";
				      document.getElementById("NIU_ExamRollNumberSAT").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIU_ExamRollNumberSAT_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>SAT Exam score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='NIU_ExamScoreSAT' id='NIU_ExamScoreSAT'  validate="validateFloat"    caption="score"   minlength="1"   maxlength="10"     tip="Enter your exam score for the selected examination"   title="Exam score"  value=''   />
				<?php if(isset($NIU_ExamScoreSAT) && $NIU_ExamScoreSAT!=""){ ?>
				  <script>
				      document.getElementById("NIU_ExamScoreSAT").value = "<?php echo str_replace("\n", '\n', $NIU_ExamScoreSAT );  ?>";
				      document.getElementById("NIU_ExamScoreSAT").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIU_ExamScoreSAT_error'></div></div>
				</div>
				</div>
			</li>
			
			<?php
			    if(isset($NIU_ExamTaken) && $NIU_ExamTaken!=""){
				if(strpos($NIU_ExamTaken,'TOEFL')!==false)
					echo "<script>checkExamTaken(document.getElementById('NIU_ExamTaken0'));</script>";
				if(strpos($NIU_ExamTaken,'IELTS')!==false)
					echo "<script>checkExamTaken(document.getElementById('NIU_ExamTaken1'));</script>";
				if(strpos($NIU_ExamTaken,'SAT')!==false)
					echo "<script>checkExamTaken(document.getElementById('NIU_ExamTaken2'));</script>";				
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
				<input type='text' name='NIU_fatherEducation' id='NIU_fatherEducation'  validate="validateStr"   required="true"   caption="education"   minlength="2"   maxlength="50"     tip="Please enter your father's education level.  Enter the class till where your father has studied."   title="Father's education level"  value=''   />
				<?php if(isset($NIU_fatherEducation) && $NIU_fatherEducation!=""){ ?>
				  <script>
				      document.getElementById("NIU_fatherEducation").value = "<?php echo str_replace("\n", '\n', $NIU_fatherEducation );  ?>";
				      document.getElementById("NIU_fatherEducation").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIU_fatherEducation_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Father's email: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='NIU_fatherEmail' id='NIU_fatherEmail'  validate="validateEmail"   required="true"   caption="email"   minlength="2"   maxlength="50"     tip="Please enter your father's email address. If your father doesn't have an email address, just enter <b>NA</b>."   title="Father's email"  value=''  allowNA="true" />
				<?php if(isset($NIU_fatherEmail) && $NIU_fatherEmail!=""){ ?>
				  <script>
				      document.getElementById("NIU_fatherEmail").value = "<?php echo str_replace("\n", '\n', $NIU_fatherEmail );  ?>";
				      document.getElementById("NIU_fatherEmail").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIU_fatherEmail_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Father's Mobile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='NIU_fatherMobile' id='NIU_fatherMobile'  validate="validateMobileInteger"   required="true"   caption="mobile number"   minlength="10"   maxlength="10"     tip="Please enter the 10 digit mobile number of your father."   title="Father's Mobile"  value=''   />
				<?php if(isset($NIU_fatherMobile) && $NIU_fatherMobile!=""){ ?>
				  <script>
				      document.getElementById("NIU_fatherMobile").value = "<?php echo str_replace("\n", '\n', $NIU_fatherMobile );  ?>";
				      document.getElementById("NIU_fatherMobile").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIU_fatherMobile_error'></div></div>
				</div>
				</div>
				
				<div class='additionalInfoRightCol'>
				<label>Father's Annual Income: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='NIU_fatherIncome' id='NIU_fatherIncome'  validate="validateFloat"   required="true"   caption="income"   minlength="2"   maxlength="12"     tip="Please enter your Father's Annual Income in Rupees. If its not applicable in your case, just enter <b>NA</b>."   title="Father's annual income"  value=''  allowNA="true" />
				<?php if(isset($NIU_fatherIncome) && $NIU_fatherIncome!=""){ ?>
				  <script>
				      document.getElementById("NIU_fatherIncome").value = "<?php echo str_replace("\n", '\n', $NIU_fatherIncome );  ?>";
				      document.getElementById("NIU_fatherIncome").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIU_fatherIncome_error'></div></div>
				</div>
				</div>
				
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Mother's education level: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='NIU_motherEducation' id='NIU_motherEducation'  validate="validateStr"   required="true"   caption="education"   minlength="2"   maxlength="50"     tip="Please enter your mother's education level.  Enter the class till where your mother has studied."   title="Mother's education level"  value=''   />
				<?php if(isset($NIU_motherEducation) && $NIU_motherEducation!=""){ ?>
				  <script>
				      document.getElementById("NIU_motherEducation").value = "<?php echo str_replace("\n", '\n', $NIU_motherEducation );  ?>";
				      document.getElementById("NIU_motherEducation").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIU_motherEducation_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Mother's email: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='NIU_motherEmail' id='NIU_motherEmail'  validate="validateEmail"   required="true"   caption="email"   minlength="2"   maxlength="50"     tip="Please enter your mother's email address. If your mother doesn't have an email address, just enter <b>NA</b>."   title="Mother's email"  value=''  allowNA="true" />
				<?php if(isset($NIU_motherEmail) && $NIU_motherEmail!=""){ ?>
				  <script>
				      document.getElementById("NIU_motherEmail").value = "<?php echo str_replace("\n", '\n', $NIU_motherEmail );  ?>";
				      document.getElementById("NIU_motherEmail").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIU_motherEmail_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Mother's Mobile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='NIU_motherMobile' id='NIU_motherMobile'  validate="validateMobileInteger"   required="true"   caption="mobile number"   minlength="10"   maxlength="10"     tip="Please enter the 10 digit mobile number of your mother. If your mother doesn't have a mobile, just enter <b>NA</b>."   title="Mother's Mobile"  value=''  allowNA="true" />
				<?php if(isset($NIU_motherMobile) && $NIU_motherMobile!=""){ ?>
				  <script>
				      document.getElementById("NIU_motherMobile").value = "<?php echo str_replace("\n", '\n', $NIU_motherMobile );  ?>";
				      document.getElementById("NIU_motherMobile").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIU_motherMobile_error'></div></div>
				</div>
				</div>
				
				<div class='additionalInfoRightCol'>
				<label>Mother's Annual Income: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='NIU_motherIncome' id='NIU_motherIncome'  validate="validateFloat"   required="true"   caption="income"   minlength="2"   maxlength="12"     tip="Please enter your Mother's Annual Income in Rupees. If its not applicable in your case, just enter <b>NA</b>."   title="Mother's annual income"  value=''  allowNA="true" />
				<?php if(isset($NIU_motherIncome) && $NIU_motherIncome!=""){ ?>
				  <script>
				      document.getElementById("NIU_motherIncome").value = "<?php echo str_replace("\n", '\n', $NIU_motherIncome );  ?>";
				      document.getElementById("NIU_motherIncome").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIU_motherIncome_error'></div></div>
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
				<input type='text' name='NIU_10thSubjects' id='NIU_10thSubjects'  validate="validateStr"   required="true"   caption="subjects"   minlength="2"   maxlength="50"     tip="Please enter the name of subjects that you studied in class 10th, seperated by a comma. For example English, Hindi, Mathematics, Science etc."   title="Class 10th Subjects"  value=''   csv="true" />
				<?php if(isset($NIU_10thSubjects) && $NIU_10thSubjects!=""){ ?>
				  <script>
				      document.getElementById("NIU_10thSubjects").value = "<?php echo str_replace("\n", '\n', $NIU_10thSubjects );  ?>";
				      document.getElementById("NIU_10thSubjects").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIU_10thSubjects_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Class 12th Subjects: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='NIU_12thSubjects' id='NIU_12thSubjects'  validate="validateStr"   required="true"   caption="subjects"   minlength="2"   maxlength="50"     tip="Please enter the name of subjects that you studied in class 12th, seperated by a comma. For example English, Hindi, Mathematics, Physics etc."   title="Class 12th Subjects"  value=''   csv="true" />
				<?php if(isset($NIU_12thSubjects) && $NIU_12thSubjects!=""){ ?>
				  <script>
				      document.getElementById("NIU_12thSubjects").value = "<?php echo str_replace("\n", '\n', $NIU_12thSubjects );  ?>";
				      document.getElementById("NIU_12thSubjects").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIU_12thSubjects_error'></div></div>
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
				<input type='text' name='NIU_mul_<?=$key?>_Subjects' id='NIU_mul_<?=$key?>_Subjects'  maxlength="200" minlength="2" validate="validateStr" caption="subjects"   required="true"        tip="Please enter the name of subjects that you studied in <?=$course?>, seperated by a comma. For example English, Hindi, Mathematics, Science etc."   title="<?=$course?> Subjects"  value=''   csv="true" />
				<?php if(${'NIU_mul_'.$key.'_Subjects'}){ ?>
				  <script>
				      document.getElementById("NIU_mul_<?=$key?>_Subjects").value = "<?php echo str_replace("\n", '\n', ${'NIU_mul_'.$key.'_Subjects'} );  ?>";
				      document.getElementById("NIU_mul_<?=$key?>_Subjects").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIU_mul_<?=$key?>_Subjects_error'></div></div>
				</div>
				</div>
	</li>
	<?php
	}
	?>
				
			<!-- Education details -->
<?php endif; ?>
			
			<li>
				<h3 class="upperCase">Qualifying exam details</h3>
			</li>

			<table width="100%" style="margin-top:10px;margin-bottom:10px;border:1px solid #000" >
				<tr class="cutom-tr">
					<td width='120px'>
						Examination
					</td>
					<td>
						Date
					</td>
					<td>
						Registration Number<br/>(if any)
					</td>
					<td>
						Score/Rank Obtained<br/>(Attach copy of rank certificate)
					</td>
				</tr>
				<tr class="cutom-tr">
					<td>
						JEE Main
					</td>
					<td>
						<div class='fieldBoxLarge'>
						<input type='text' name='jeeDateOfExaminationAdditional' id='jeeDateOfExaminationAdditional' readonly   minlength='1' maxlength='10'  validate="validateDateForms"   caption='date'    tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('jeeDateOfExaminationAdditional'),'jeeDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />
						&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='jeeDateOfExaminationAdditional_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('jeeDateOfExaminationAdditional'),'jeeDateOfExaminationAdditional_dateImg','dd/MM/yyyy'); return false;" />
						<?php if(isset($jeeDateOfExaminationAdditional) && $jeeDateOfExaminationAdditional!=""){ ?>
						  <script>
						      document.getElementById("jeeDateOfExaminationAdditional").value = "<?php echo str_replace("\n", '\n', $jeeDateOfExaminationAdditional );  ?>";
						      document.getElementById("jeeDateOfExaminationAdditional").style.color = "";
						  </script>
						<?php } ?>
						
						<div style='display:none'><div class='errorMsg' id= 'jeeDateOfExaminationAdditional_error'></div></div>
						</div>
					</td>
					<td>
						<div class='fieldBoxLarge'>
						<input type='text' name='jeeRollNumberAdditional' id='jeeRollNumberAdditional'    maxlength="50" minlength="2" caption="number" validate="validateStr"  tip="Mention your roll number for the exam."   value=''   />
						<?php if(isset($jeeRollNumberAdditional) && $jeeRollNumberAdditional!=""){ ?>
						  <script>
						      document.getElementById("jeeRollNumberAdditional").value = "<?php echo str_replace("\n", '\n', $jeeRollNumberAdditional );  ?>";
						      document.getElementById("jeeRollNumberAdditional").style.color = "";
						  </script>
						<?php } ?>
						
						<div style='display:none'><div class='errorMsg' id= 'jeeRollNumberAdditional_error'></div></div>
						</div>
					</td>
					<td>
						<div class='fieldBoxLarge'>
						<input type='text' name='jeeScoreAdditional' id='jeeScoreAdditional'    maxlength="7" minlength="1" caption="score" validate="validateInteger"       tip="Mention your Marks in the exam. If you don't know your Marks, you can leave this field blank."   value=''   />
						<?php if(isset($jeeScoreAdditional) && $jeeScoreAdditional!=""){ ?>
						  <script>
						      document.getElementById("jeeScoreAdditional").value = "<?php echo str_replace("\n", '\n', $jeeScoreAdditional );  ?>";
						      document.getElementById("jeeScoreAdditional").style.color = "";
						  </script>
						<?php } ?>
						
						<div style='display:none'><div class='errorMsg' id= 'jeeScoreAdditional_error'></div></div>
						</div>
					</td>
				<tr>
				<tr class="cutom-tr">
					<td>
						JEE Advanced
					</td>
					<td>
						<div class='fieldBoxLarge'>
						<input type='text' name='NIU_jeeAdvancedDate' id='NIU_jeeAdvancedDate' readonly maxlength='10'         tip="Please enter JEE Advanced date, If applicable."   title="JEE Advanced Date"    onmouseover="showTipOnline('Please enter JEE Advanced date, If applicable.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('NIU_jeeAdvancedDate'),'NIU_jeeAdvancedDate_dateImg','dd/MM/yyyy');" />
						&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='NIU_jeeAdvancedDate_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('NIU_jeeAdvancedDate'),'NIU_jeeAdvancedDate_dateImg','dd/MM/yyyy'); return false;" />
						<?php if(isset($NIU_jeeAdvancedDate) && $NIU_jeeAdvancedDate!=""){ ?>
						  <script>
						      document.getElementById("NIU_jeeAdvancedDate").value = "<?php echo str_replace("\n", '\n', $NIU_jeeAdvancedDate );  ?>";
						      document.getElementById("NIU_jeeAdvancedDate").style.color = "";
						  </script>
						<?php } ?>
						
						<div style='display:none'><div class='errorMsg' id= 'NIU_jeeAdvancedDate_error'></div></div>
						</div>
					</td>
					<td>
						<div class='fieldBoxLarge'>
						<input type='text' name='NIU_jeeAdvancedRegistrationNumber' id='NIU_jeeAdvancedRegistrationNumber'  validate="validateStr"    caption="number"   minlength="2"   maxlength="20"     tip="Please enter JEE Advanced Registration Number, If applicable."   title="JEE Advanced Registration Number"  value=''   />
						<?php if(isset($NIU_jeeAdvancedRegistrationNumber) && $NIU_jeeAdvancedRegistrationNumber!=""){ ?>
						  <script>
						      document.getElementById("NIU_jeeAdvancedRegistrationNumber").value = "<?php echo str_replace("\n", '\n', $NIU_jeeAdvancedRegistrationNumber );  ?>";
						      document.getElementById("NIU_jeeAdvancedRegistrationNumber").style.color = "";
						  </script>
						<?php } ?>
						
						<div style='display:none'><div class='errorMsg' id= 'NIU_jeeAdvancedRegistrationNumber_error'></div></div>
						</div>
					</td>
					<td>
						<div class='fieldBoxLarge'>
						<input type='text' name='NIU_jeeAdvancedScore' id='NIU_jeeAdvancedScore'  validate="validateFloat"    caption="score"   minlength="1"   maxlength="10"     tip="Please enter JEE Advanced Score/Rank, If applicable."   title="JEE Advanced Score/Rank"  value=''   />
						<?php if(isset($NIU_jeeAdvancedScore) && $NIU_jeeAdvancedScore!=""){ ?>
						  <script>
						      document.getElementById("NIU_jeeAdvancedScore").value = "<?php echo str_replace("\n", '\n', $NIU_jeeAdvancedScore );  ?>";
						      document.getElementById("NIU_jeeAdvancedScore").style.color = "";
						  </script>
						<?php } ?>
						
						<div style='display:none'><div class='errorMsg' id= 'NIU_jeeAdvancedScore_error'></div></div>
						</div>
					</td>
				<tr>
				<tr class="cutom-tr">
					<td>
						UPTU
					</td>
					<td>
						<div class='fieldBoxLarge'>
						<input type='text' name='NIU_UPTUDate' id='NIU_UPTUDate' readonly maxlength='10'         tip="Please enter UPTU date, If applicable."   title="UPTU Date"    onmouseover="showTipOnline('Please enter UPTU date, If applicable.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('NIU_UPTUDate'),'NIU_UPTUDate_dateImg','dd/MM/yyyy');" />
						&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='NIU_UPTUDate_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('NIU_UPTUDate'),'NIU_UPTUDate_dateImg','dd/MM/yyyy'); return false;" />
						<?php if(isset($NIU_UPTUDate) && $NIU_UPTUDate!=""){ ?>
						  <script>
						      document.getElementById("NIU_UPTUDate").value = "<?php echo str_replace("\n", '\n', $NIU_UPTUDate );  ?>";
						      document.getElementById("NIU_UPTUDate").style.color = "";
						  </script>
						<?php } ?>
						
						<div style='display:none'><div class='errorMsg' id= 'NIU_UPTUDate_error'></div></div>
						</div>
					</td>
					<td>
						<div class='fieldBoxLarge'>
						<input type='text' name='NIU_UPTURegistrationNumber' id='NIU_UPTURegistrationNumber'  validate="validateStr"    caption="number"   minlength="2"   maxlength="20"     tip="Please enter UPTU Registration Number, If applicable."   title="UPTU Registration Number"  value=''   />
						<?php if(isset($NIU_UPTURegistrationNumber) && $NIU_UPTURegistrationNumber!=""){ ?>
						  <script>
						      document.getElementById("NIU_UPTURegistrationNumber").value = "<?php echo str_replace("\n", '\n', $NIU_UPTURegistrationNumber );  ?>";
						      document.getElementById("NIU_UPTURegistrationNumber").style.color = "";
						  </script>
						<?php } ?>
						
						<div style='display:none'><div class='errorMsg' id= 'NIU_UPTURegistrationNumber_error'></div></div>
						</div>
					</td>
					<td>
						<div class='fieldBoxLarge'>
						<input type='text' name='NIU_UPTURank' id='NIU_UPTURank'  validate="validateFloat"    caption="score"   minlength="1"   maxlength="10"     tip="Please enter UPTU Score/Rank, If applicable."   title="UPTU Score/Rank"  value=''   />
						<?php if(isset($NIU_UPTURank) && $NIU_UPTURank!=""){ ?>
						  <script>
						      document.getElementById("NIU_UPTURank").value = "<?php echo str_replace("\n", '\n', $NIU_UPTURank );  ?>";
						      document.getElementById("NIU_UPTURank").style.color = "";
						  </script>
						<?php } ?>
						
						<div style='display:none'><div class='errorMsg' id= 'NIU_UPTURank_error'></div></div>
						</div>
					</td>
				<tr>
				<tr class="cutom-tr">
					<td>
						<div class='fieldBoxLarge'>
						<input type='text' name='NIU_OtherExamName' id='NIU_OtherExamName'  validate="validateStr"    caption="details"   minlength="2"   maxlength="50"     tip="Enter the Examination name"   title="Exam Name"  value='Other Exam'   default = 'Other Exam' onfocus='checkTextElementOnTransition(this,"focus");' blurMethod='checkTextElementOnTransition(this,"blur");'  /><script>
						document.getElementById("NIU_OtherExamName").style.color = "#ADA6AD";
						</script>
						<?php if(isset($NIU_OtherExamName) && $NIU_OtherExamName!=""){ ?>
						  <script>
						      document.getElementById("NIU_OtherExamName").value = "<?php echo str_replace("\n", '\n', $NIU_OtherExamName );  ?>";
						      document.getElementById("NIU_OtherExamName").style.color = "";
						  </script>
						<?php } ?>
						
						<div style='display:none'><div class='errorMsg' id= 'NIU_OtherExamName_error'></div></div>
						</div>						
					</td>
					<td>
						<div class='fieldBoxLarge'>
						<input type='text' name='NIU_OtherDate' id='NIU_OtherDate' readonly maxlength='10'         tip="Please enter date, If applicable."   title="Other Date"    onmouseover="showTipOnline('Please enter date, If applicable.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('NIU_OtherDate'),'NIU_OtherDate_dateImg','dd/MM/yyyy');" />
						&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='NIU_OtherDate_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('NIU_OtherDate'),'NIU_OtherDate_dateImg','dd/MM/yyyy'); return false;" />
						<?php if(isset($NIU_OtherDate) && $NIU_OtherDate!=""){ ?>
						  <script>
						      document.getElementById("NIU_OtherDate").value = "<?php echo str_replace("\n", '\n', $NIU_OtherDate );  ?>";
						      document.getElementById("NIU_OtherDate").style.color = "";
						  </script>
						<?php } ?>
						
						<div style='display:none'><div class='errorMsg' id= 'NIU_OtherDate_error'></div></div>
						</div>
					</td>
					<td>
						<div class='fieldBoxLarge'>
						<input type='text' name='NIU_OtherRegistrationNumber' id='NIU_OtherRegistrationNumber'  validate="validateStr"    caption="number"   minlength="2"   maxlength="20"     tip="Please enter Registration Number, If applicable."   title="Other Registration Number"  value=''   />
						<?php if(isset($NIU_OtherRegistrationNumber) && $NIU_OtherRegistrationNumber!=""){ ?>
						  <script>
						      document.getElementById("NIU_OtherRegistrationNumber").value = "<?php echo str_replace("\n", '\n', $NIU_OtherRegistrationNumber );  ?>";
						      document.getElementById("NIU_OtherRegistrationNumber").style.color = "";
						  </script>
						<?php } ?>
						
						<div style='display:none'><div class='errorMsg' id= 'NIU_OtherRegistrationNumber_error'></div></div>
						</div>
					</td>
					<td>
						<div class='fieldBoxLarge'>
						<input type='text' name='NIU_OtherRank' id='NIU_OtherRank'  validate="validateFloat"    caption="score"   minlength="1"   maxlength="10"     tip="Please enter Score/Rank, If applicable."   title="Other Score"  value=''   />
						<?php if(isset($NIU_OtherRank) && $NIU_OtherRank!=""){ ?>
						  <script>
						      document.getElementById("NIU_OtherRank").value = "<?php echo str_replace("\n", '\n', $NIU_OtherRank );  ?>";
						      document.getElementById("NIU_OtherRank").style.color = "";
						  </script>
						<?php } ?>
						
						<div style='display:none'><div class='errorMsg' id= 'NIU_OtherRank_error'></div></div>
						</div>
					</td>
				<tr>
			</table>

<?php if($action != 'updateScore'):?>

			<li>
				<h3 class="upperCase">Other Details</h3>
				</li>
				<li>
				<div class='additionalInfoLeftCol'>
				<label>Hostel accomodation required?: </label>
				<div class='fieldBoxLarge'>
				<input type='radio'   required="true"   name='NIU_hostel' id='NIU_hostel0'   value='Yes'  checked  title="Hostel accomodation required?"   onmouseover="showTipOnline('Please mention if you require hostel accomodation',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please mention if you require hostel accomodation',this);" onmouseout="hidetip();" >Yes</span>&nbsp;&nbsp;
				<input type='radio'   required="true"   name='NIU_hostel' id='NIU_hostel1'   value='No'    title="Hostel accomodation required?"   onmouseover="showTipOnline('Please mention if you require hostel accomodation',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please mention if you require hostel accomodation',this);" onmouseout="hidetip();" >No</span>&nbsp;&nbsp;
				<?php if(isset($NIU_hostel) && $NIU_hostel!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["NIU_hostel"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $NIU_hostel;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIU_hostel_error'></div></div>
				</div>
				</div>
			</li>


			<li>
				<div class='additionalInfoLeftCol'>
				<label>Type of hostel accomodation required: </label>
				<div class='fieldBoxLarge'>
				<input type='radio'   required="true"   name='NIU_AC_NONAC' id='NIU_AC_NONAC0'   value='AC'  checked  title="Type of hostel accomodation required"   onmouseover="showTipOnline('Please mention the type of hostel accomodation that you require',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please mention the type of hostel accomodation that you require',this);" onmouseout="hidetip();" >AC</span>&nbsp;&nbsp;
				<input type='radio'   required="true"   name='NIU_AC_NONAC' id='NIU_AC_NONAC1'   value='Non AC'    title="Type of hostel accomodation required"   onmouseover="showTipOnline('Please mention the type of hostel accomodation that you require',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please mention the type of hostel accomodation that you require',this);" onmouseout="hidetip();" >Non AC</span>&nbsp;&nbsp;
				<?php if(isset($NIU_AC_NONAC) && $NIU_AC_NONAC!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["NIU_AC_NONAC"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $NIU_AC_NONAC;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIU_AC_NONAC_error'></div></div>
				</div>
				</div>
			</li>


			<li>
				<div class='additionalInfoLeftCol'>
				<label>Transport Required?: </label>
				<div class='fieldBoxLarge'>
				<input type='radio'   required="true"   name='NIU_transportRequired' id='NIU_transportRequired0'   value='Yes'  checked  title="Transport Required?"   onmouseover="showTipOnline('Please mention if you require transport for commuting to and from the institute',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please mention if you require transport for commuting to and from the institute',this);" onmouseout="hidetip();" >Yes</span>&nbsp;&nbsp;
				<input type='radio'   required="true"   name='NIU_transportRequired' id='NIU_transportRequired1'   value='No'    title="Transport Required?"   onmouseover="showTipOnline('Please mention if you require transport for commuting to and from the institute',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please mention if you require transport for commuting to and from the institute',this);" onmouseout="hidetip();" >No</span>&nbsp;&nbsp;
				<?php if(isset($NIU_transportRequired) && $NIU_transportRequired!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["NIU_transportRequired"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $NIU_transportRequired;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIU_transportRequired_error'></div></div>
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
				<textarea style='width: 624px;' name='NIU_scholarship' id='NIU_scholarship'  validate="validateStr"    caption="details"   minlength="2"   maxlength="1000"     tip="If you were ever awarded a scholarship, please provide complete details of the scholarship here. If not, then leave this field blank."   title="Have you ever been awarded a scholarship? If yes, give details"   ></textarea>
				<?php if(isset($NIU_scholarship) && $NIU_scholarship!=""){ ?>
				  <script>
				      document.getElementById("NIU_scholarship").value = "<?php echo str_replace("\n", '\n', $NIU_scholarship );  ?>";
				      document.getElementById("NIU_scholarship").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIU_scholarship_error'></div></div>
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
						<input type='text' name='NIU_awards1Name' id='NIU_awards1Name'  validate="validateStr"    caption="details"   minlength="2"   maxlength="100"     tip="If you were ever given an award, please provide the details. If not, then leave these fields blank."   title="Name of Award"  value=''   />
						<?php if(isset($NIU_awards1Name) && $NIU_awards1Name!=""){ ?>
						  <script>
						      document.getElementById("NIU_awards1Name").value = "<?php echo str_replace("\n", '\n', $NIU_awards1Name );  ?>";
						      document.getElementById("NIU_awards1Name").style.color = "";
						  </script>
						<?php } ?>
						
						<div style='display:none'><div class='errorMsg' id= 'NIU_awards1Name_error'></div></div>
						</div>
					</td>
					<td>
						<div class='fieldBoxLarge'>
						<input type='text' name='NIU_awards1Year' id='NIU_awards1Year'  validate="validateInteger"    caption="details"   minlength="4"   maxlength="4"     tip="If you were ever given an award, please provide the details. If not, then leave these fields blank."   title="Year of Award"  value=''   />
						<?php if(isset($NIU_awards1Year) && $NIU_awards1Year!=""){ ?>
						  <script>
						      document.getElementById("NIU_awards1Year").value = "<?php echo str_replace("\n", '\n', $NIU_awards1Year );  ?>";
						      document.getElementById("NIU_awards1Year").style.color = "";
						  </script>
						<?php } ?>
						
						<div style='display:none'><div class='errorMsg' id= 'NIU_awards1Year_error'></div></div>
						</div>
					</td>
					<td>
						<div class='fieldBoxLarge'>
						<input type='text' name='NIU_awards1For' id='NIU_awards1For'  validate="validateStr"    caption="details"   minlength="2"   maxlength="100"     tip="If you were ever given an award, please provide the details. If not, then leave these fields blank."   title="For"  value=''   />
						<?php if(isset($NIU_awards1For) && $NIU_awards1For!=""){ ?>
						  <script>
						      document.getElementById("NIU_awards1For").value = "<?php echo str_replace("\n", '\n', $NIU_awards1For );  ?>";
						      document.getElementById("NIU_awards1For").style.color = "";
						  </script>
						<?php } ?>
						
						<div style='display:none'><div class='errorMsg' id= 'NIU_awards1For_error'></div></div>
						</div>
					</td>
				</tr>

				<tr class="cutom-tr">
					<td>
						Second
					</td>
					<td>
						<div class='fieldBoxLarge'>
						<input type='text' name='NIU_awards2Name' id='NIU_awards2Name'  validate="validateStr"    caption="details"   minlength="2"   maxlength="100"     tip="If you were ever given an award, please provide the details. If not, then leave these fields blank."   title="Name of Award"  value=''   />
						<?php if(isset($NIU_awards2Name) && $NIU_awards2Name!=""){ ?>
						  <script>
						      document.getElementById("NIU_awards2Name").value = "<?php echo str_replace("\n", '\n', $NIU_awards2Name );  ?>";
						      document.getElementById("NIU_awards2Name").style.color = "";
						  </script>
						<?php } ?>
						
						<div style='display:none'><div class='errorMsg' id= 'NIU_awards2Name_error'></div></div>
						</div>
					</td>
					<td>
						<div class='fieldBoxLarge'>
						<input type='text' name='NIU_awards2Year' id='NIU_awards2Year'  validate="validateInteger"    caption="details"   minlength="4"   maxlength="4"     tip="If you were ever given an award, please provide the details. If not, then leave these fields blank."   title="Year of Award"  value=''   />
						<?php if(isset($NIU_awards2Year) && $NIU_awards2Year!=""){ ?>
						  <script>
						      document.getElementById("NIU_awards2Year").value = "<?php echo str_replace("\n", '\n', $NIU_awards2Year );  ?>";
						      document.getElementById("NIU_awards2Year").style.color = "";
						  </script>
						<?php } ?>
						
						<div style='display:none'><div class='errorMsg' id= 'NIU_awards2Year_error'></div></div>
						</div>
					</td>
					<td>
						<div class='fieldBoxLarge'>
						<input type='text' name='NIU_awards2For' id='NIU_awards2For'  validate="validateStr"    caption="details"   minlength="2"   maxlength="100"     tip="If you were ever given an award, please provide the details. If not, then leave these fields blank."   title="For"  value=''   />
						<?php if(isset($NIU_awards2For) && $NIU_awards2For!=""){ ?>
						  <script>
						      document.getElementById("NIU_awards2For").value = "<?php echo str_replace("\n", '\n', $NIU_awards2For );  ?>";
						      document.getElementById("NIU_awards2For").style.color = "";
						  </script>
						<?php } ?>
						
						<div style='display:none'><div class='errorMsg' id= 'NIU_awards2For_error'></div></div>
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
						<input type='text' name='NIU_activity1Name' id='NIU_activity1Name'  validate="validateStr"    caption="details"   minlength="2"   maxlength="100"     tip="If you were involved in any extra-curricular activity during your acedemic years, mention the details here. If this does not apply to you, leve these fields blank."   title="Name of activity"  value=''   />
						<?php if(isset($NIU_activity1Name) && $NIU_activity1Name!=""){ ?>
						  <script>
						      document.getElementById("NIU_activity1Name").value = "<?php echo str_replace("\n", '\n', $NIU_activity1Name );  ?>";
						      document.getElementById("NIU_activity1Name").style.color = "";
						  </script>
						<?php } ?>
						
						<div style='display:none'><div class='errorMsg' id= 'NIU_activity1Name_error'></div></div>
						</div>
					</td>

					<td>
						<div class='fieldBoxLarge'>
						<input type='text' name='NIU_activity1Period' id='NIU_activity1Period'  validate="validateStr"    caption="details"   minlength="2"   maxlength="100"     tip="If you were involved in any extra-curricular activity during your acedemic years, mention the details here. If this does not apply to you, leve these fields blank."   title="Period of activity"  value=''   />
						<?php if(isset($NIU_activity1Period) && $NIU_activity1Period!=""){ ?>
						  <script>
						      document.getElementById("NIU_activity1Period").value = "<?php echo str_replace("\n", '\n', $NIU_activity1Period );  ?>";
						      document.getElementById("NIU_activity1Period").style.color = "";
						  </script>
						<?php } ?>
						
						<div style='display:none'><div class='errorMsg' id= 'NIU_activity1Period_error'></div></div>
						</div>
					</td>

					<td>
						<div class='fieldBoxLarge'>
						<input type='text' name='NIU_activity1Position' id='NIU_activity1Position'  validate="validateStr"    caption="details"   minlength="2"   maxlength="100"     tip="If you were involved in any extra-curricular activity during your acedemic years, mention the details here. If this does not apply to you, leve these fields blank."   title="Name of position"  value=''   />
						<?php if(isset($NIU_activity1Position) && $NIU_activity1Position!=""){ ?>
						  <script>
						      document.getElementById("NIU_activity1Position").value = "<?php echo str_replace("\n", '\n', $NIU_activity1Position );  ?>";
						      document.getElementById("NIU_activity1Position").style.color = "";
						  </script>
						<?php } ?>
						
						<div style='display:none'><div class='errorMsg' id= 'NIU_activity1Position_error'></div></div>
						</div>
					</td>
				</tr>
				<tr class="cutom-tr">
					<td width='120px'>
					Second
					</td>
					<td>
						<div class='fieldBoxLarge'>
						<input type='text' name='NIU_activity2Name' id='NIU_activity2Name'  validate="validateStr"    caption="details"   minlength="2"   maxlength="100"     tip="If you were involved in any extra-curricular activity during your acedemic years, mention the details here. If this does not apply to you, leve these fields blank."   title="Name of activity"  value=''   />
						<?php if(isset($NIU_activity2Name) && $NIU_activity2Name!=""){ ?>
						  <script>
						      document.getElementById("NIU_activity2Name").value = "<?php echo str_replace("\n", '\n', $NIU_activity2Name );  ?>";
						      document.getElementById("NIU_activity2Name").style.color = "";
						  </script>
						<?php } ?>
						
						<div style='display:none'><div class='errorMsg' id= 'NIU_activity2Name_error'></div></div>
						</div>
					</td>

					<td>
						<div class='fieldBoxLarge'>
						<input type='text' name='NIU_activity2Period' id='NIU_activity2Period'  validate="validateStr"    caption="details"   minlength="2"   maxlength="100"     tip="If you were involved in any extra-curricular activity during your acedemic years, mention the details here. If this does not apply to you, leve these fields blank."   title="Period of activity"  value=''   />
						<?php if(isset($NIU_activity2Period) && $NIU_activity2Period!=""){ ?>
						  <script>
						      document.getElementById("NIU_activity2Period").value = "<?php echo str_replace("\n", '\n', $NIU_activity2Period );  ?>";
						      document.getElementById("NIU_activity2Period").style.color = "";
						  </script>
						<?php } ?>
						
						<div style='display:none'><div class='errorMsg' id= 'NIU_activity2Period_error'></div></div>
						</div>
					</td>

					<td>
						<div class='fieldBoxLarge'>
						<input type='text' name='NIU_activity2Position' id='NIU_activity2Position'  validate="validateStr"    caption="details"   minlength="2"   maxlength="100"     tip="If you were involved in any extra-curricular activity during your acedemic years, mention the details here. If this does not apply to you, leve these fields blank."   title="Name of position"  value=''   />
						<?php if(isset($NIU_activity2Position) && $NIU_activity2Position!=""){ ?>
						  <script>
						      document.getElementById("NIU_activity2Position").value = "<?php echo str_replace("\n", '\n', $NIU_activity2Position );  ?>";
						      document.getElementById("NIU_activity2Position").style.color = "";
						  </script>
						<?php } ?>
						
						<div style='display:none'><div class='errorMsg' id= 'NIU_activity2Position_error'></div></div>
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
				<textarea style='width: 624px;' name='NIU_personalGoals' id='NIU_personalGoals'  validate="validateStr"   required="true"   caption="personal goals"   minlength="2"   maxlength="1000"     tip="Please write a short essay on what are your personal and professional goals and how will the course applied for by you at Noida International University help you to accomplish the same?"   title="What are your personal and professional goals and how will the course applied for by you at Noida International University help you to accomplish the same?"   ></textarea>
				<?php if(isset($NIU_personalGoals) && $NIU_personalGoals!=""){ ?>
				  <script>
				      document.getElementById("NIU_personalGoals").value = "<?php echo str_replace("\n", '\n', $NIU_personalGoals );  ?>";
				      document.getElementById("NIU_personalGoals").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIU_personalGoals_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Name and describe your two biggest strengths and weaknesses.: </label>
				<div class='fieldBoxLarge'>
				<textarea style='width: 624px;' name='NIU_strengths' id='NIU_strengths'  validate="validateStr"   required="true"   caption="strengths and weaknesses"   minlength="2"   maxlength="1000"     tip="Write a short essay describing 2 of your biggest strengths and 2 weaknesses."   title="Name and describe your two biggest strengths and weaknesses."   ></textarea>
				<?php if(isset($NIU_strengths) && $NIU_strengths!=""){ ?>
				  <script>
				      document.getElementById("NIU_strengths").value = "<?php echo str_replace("\n", '\n', $NIU_strengths );  ?>";
				      document.getElementById("NIU_strengths").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIU_strengths_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>How do you intend to engage, enrich and evolve the core culture of NIU?: </label>
				<div class='fieldBoxLarge'>
				<textarea style='width: 624px;' name='NIU_culture' id='NIU_culture'  validate="validateStr"   required="true"   caption="details"   minlength="2"   maxlength="1000"     tip="Write a short essay on how do you intend to engage, enrich and evolve the core culture of NIU?"   title="How do you intend to engage, enrich and evolve the core culture of NIU?"   ></textarea>
				<?php if(isset($NIU_culture) && $NIU_culture!=""){ ?>
				  <script>
				      document.getElementById("NIU_culture").value = "<?php echo str_replace("\n", '\n', $NIU_culture );  ?>";
				      document.getElementById("NIU_culture").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIU_culture_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>What does "Revolutionizing Learning Experience" mean to you?: </label>
				<div class='fieldBoxLarge'>
				<textarea style='width: 624px;' name='NIU_experience' id='NIU_experience'  validate="validateStr"   required="true"   caption="details"   minlength="2"   maxlength="1000"     tip='Please write a short essay on what does "Revolutionizing Learning Experience" mean to you?'   title='What does "Revolutionizing Learning Experience" mean to you?'   ></textarea>
				<?php if(isset($NIU_experience) && $NIU_experience!=""){ ?>
				  <script>
				      document.getElementById("NIU_experience").value = "<?php echo str_replace("\n", '\n', $NIU_experience );  ?>";
				      document.getElementById("NIU_experience").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'NIU_experience_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<h3 class="upperCase">Disclaimer</h3>
				<label style="font-weight:normal; padding-top:0">Declaration:</label>
				<div class='fieldBoxLarge' style="width:620px; color:#666666; font-style:italic">
1. I have read and understood the full requirements of the course, eligibility criteria, terms and conditions and
other important information as indicated in the Prospectus/website.<br/>
2. I confirm that the information furnished by me in this Application Form is true to the best of my knowledge. I
understand that any false or misleading statement given by me may lead to the cancellation of admission or
expulsion fromthe course at any stage.<br/>
3. I undertake to abide by the rules and regulations of the NIU College of Engineering and Design as
prescribed from time to time. If I violate at any point of time any of the stipulated rules and regulations, then the
University is free to initiate appropriate disciplinary action against me.
				</div>
				<div class='additionalInfoLeftCol'>
				<label>I agree to the terms stated above: </label>
				<div class='fieldBoxLarge'>
				<input type='checkbox'   validate="validateChecked" checked  required="true"   name='NIU_agreeToTerms[]' id='NIU_agreeToTerms0'   value=''    title="I agree to the terms stated above"   onmouseover="showTipOnline('Please check to accept terms',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please check to accept terms',this);" onmouseout="hidetip();" ></span>&nbsp;&nbsp;
				<?php if(isset($NIU_agreeToTerms) && $NIU_agreeToTerms!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["NIU_agreeToTerms[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$NIU_agreeToTerms);
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
				
				<div style='display:none'><div class='errorMsg' id= 'NIU_agreeToTerms0_error'></div></div>
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
      if( isset($NIU_Nationality) && $NIU_Nationality!="INDIAN" ){
	      echo "<script>checkPassportDetails();</script>";
      }else if( isset($NIU_nationalityChild) && $NIU_nationalityChild!="INDIAN" ){
	      echo "<script>checkPassportDetails();</script>";
      }
?>
  
