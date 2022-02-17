<div class='formChildWrapper'>
	<div class='formSection'>
		<ul>
			<li>
				<h3 class="upperCase">Exam Details</h3>
				<div class='additionalInfoLeftCol'>
				<label>Please enter your registration number for CAT 2012: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='catRollNumberAdditional' id='catRollNumberAdditional'   required="true"     validate="validateCAT" minlength="9" maxlength="9" caption="CAT registration number"   tip="Enter your CAT registration details. CAT 2012 Registration Number should be nine characters. The first two characters will be alphabets and the remaining seven will be numbers."   value=''   />
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

			<?php if($action != 'updateScore'):?>	
			<li>
				<h3 class="upperCase">Additional Course Information</h3>
				<div class='additionalInfoLeftCol'>
				<label>Course Code: </label>
				<div class='fieldBoxLarge'>
				<select onChange="changeValue(this);" style="width:134px;" name='courseCodeFORE' id='courseCodeFORE'    tip="Please select your course preference. Application fee for each course is Rs. 1650. If you select BOTH, the application fee will be Rs. 3300."    validate="validateSelect"   required="true"   caption="Course Code"   onmouseover="showTipOnline('Please select your course preference. Application fee for each course is Rs. 1650. If you select BOTH, the application fee will be Rs. 3300.',this);" onmouseout="hidetip();" ><option value='' selected>Select</option><option value='PGDM' >PGDM</option><option value='PGDM-IB' >PGDM-IB</option><option value='BOTH' >BOTH</option></select>
				<?php if(isset($courseCodeFORE) && $courseCodeFORE!=""){ ?>
			      <script>
				  var selObj = document.getElementById("courseCodeFORE"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $courseCodeFORE;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'courseCodeFORE_error'></div></div>
                                <div style="margin-top:5px;font-size:12px;color:grey;display:none;" id="codeDiv"></div>
				<script>
					function changeValue(obj){
						if(obj.selectedIndex==0){
							$('codeDiv').style.display = 'none';
						}
                                                else if(obj.selectedIndex==1 || obj.selectedIndex==2){
                                                        $('codeDiv').style.display = '';
							$('codeDiv').innerHTML = 'Application fee: Rs. 1650';
                                                }
                                                else if(obj.selectedIndex==3){
                                                        $('codeDiv').style.display = '';
                                                        $('codeDiv').innerHTML = 'Application fee: Rs. 3300';
                                                }					
					}
					changeValue($('courseCodeFORE'));
				</script>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Hostel Accommodation Required: </label>
				<div class='fieldBoxLarge'>
				<select style="width:134px;" name='hostelFORE' id='hostelFORE'    tip="If you require hostel accomodation, please select Yes, otherwise select No"    validate="validateSelect"   required="true"   caption="Hostel Accommodation Requirement"   onmouseover="showTipOnline('If you require hostel accomodation, please select Yes, otherwise select No',this);" onmouseout="hidetip();" ><option value='No' selected>No</option><option value='Yes' >Yes</option></select>
				<?php if(isset($hostelFORE) && $hostelFORE!=""){ ?>
			      <script>
				  var selObj = document.getElementById("hostelFORE"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $hostelFORE;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'hostelFORE_error'></div></div>
				</div>
				</div>
			</li>

			<?php if(is_array($gdpiLocations)): ?>
			<li>
		            	<div class="additionalInfoLeftCol">
				<label style="font-weight:normal">Choice of Interview center: </label>
				<div class='fieldBoxLarge'>
					<select style="width:134px;" name='preferredGDPILocation' id='preferredGDPILocation' validate="validateStr"  minlength="1"   maxlength="1500"  required="true" caption="Interview center">
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
		                </div>
			</li>
			<?php endif; ?>
			
			<li>
				<h3 class="upperCase">Application Category</h3>
				<div class='additionalInfoLeftCol' style="width:800px;">
				<label>Category: </label>
				<div class='fieldBoxLarge' style="width:480px;">
				<input type='radio' name='categoryFORE' id='categoryFORE0' onclick="$('messageDiv').style.display='none';"  value='Self Sponsored'  checked   onmouseover="showTipOnline('Please select your application category. If you are a self sponsored candidate, select self sponsored. If your organization is sponsoring this course, please select Company Sponsored.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select your application category. If you are a self sponsored candidate, select self sponsored. If your organization is sponsoring this course, please select Company Sponsored.',this);" onmouseout="hidetip();" >Self Sponsored</span>&nbsp;&nbsp;
				<input type='radio' name='categoryFORE' id='categoryFORE1' onclick="$('messageDiv').style.display='';"  value='Company Sponsored'     onmouseover="showTipOnline('Please select your application category. If you are a self sponsored candidate, select self sponsored. If your organization is sponsoring this course, please select Company Sponsored.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select your application category. If you are a self sponsored candidate, select self sponsored. If your organization is sponsoring this course, please select Company Sponsored.',this);" onmouseout="hidetip();" >Company Sponsored</span>&nbsp;&nbsp;
				<?php if(isset($categoryFORE) && $categoryFORE!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["categoryFORE"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $categoryFORE;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'categoryFORE_error'></div></div>
				<div style="margin-top:5px;font-size:12px;color:grey;display:none;" id="messageDiv">If you are a Company Sponsored candidate, Please download form from the following link and send the duly filled form to the address mentioned in the document: (<a href="http://www.fsm.ac.in/pdf/Company-sponsored-certificate.pdf" target="_blank">http://www.fsm.ac.in/pdf/Company-sponsored-certificate.pdf</a>)</div>
				</div>
				</div>
			</li>
			<script>
				if($('categoryFORE1').checked) $('messageDiv').style.display='';
			</script>

			<li>
				<h3 class="upperCase">Education (additional information about your education)</h3>
				<div class='additionalInfoLeftCol'>
				<label>Class 12th stream: </label>
				<div class='fieldBoxLarge'>
				<select style="width:134px;" name='class12StreamFORE' id='class12StreamFORE'    tip="Please select your stream in class 12th"    validate="validateSelect"   required="true"   caption="Stream"   onmouseover="showTipOnline('Please select your stream in class 12th',this);" onmouseout="hidetip();" ><option value='' selected>Select</option><option value='Science' >Science</option><option value='Humanities' >Humanities</option><option value='Commerce' >Commerce</option></select>
				<?php if(isset($class12StreamFORE) && $class12StreamFORE!=""){ ?>
			      <script>
				  var selObj = document.getElementById("class12StreamFORE"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $class12StreamFORE;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'class12StreamFORE_error'></div></div>
				</div>
				</div>
			</li>
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
				<label><?php echo $graduationCourseName;?> stream: </label>
				<div class='fieldBoxLarge'>
				<select style="width:134px;" name='gradStreamFORE' id='gradStreamFORE'    tip="Select the Academic Stream for your graduation here."    validate="validateSelect"   required="true"   caption="Stream"   onmouseover="showTipOnline('Select the Academic Stream for your graduation here.',this);" onmouseout="hidetip();" ><option value=''>Select</option><option value='Art'>Art</option><option value='Commerce' >Commerce</option><option value='Science' >Science</option><option value='Agriculture' >Agriculture</option><option value='Engg.' >Engg.</option><option value='Technology' >Technology</option><option value='Medicine' >Medicine</option><option value='BBA' >BBA</option><option value='BBS(Management)' >BBS(Management)</option><option value='Behavioral Science' >Behavioral Science</option><option value='Pharmacy' >Pharmacy</option><option value='BCA' >BCA</option><option value='BHM' >BHM</option><option value='BIT-BIS' >BIT-BIS</option><option value='LLB' >LLB</option><option value='BJMC' >BJMC</option><option value='BFA' >BFA</option><option value='Others' >Others</option></select>
				<?php if(isset($gradStreamFORE) && $gradStreamFORE!=""){ ?>
			      <script>
				  var selObj = document.getElementById("gradStreamFORE"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $gradStreamFORE;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradStreamFORE_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label><?php echo $graduationCourseName;?> subjects: </label>
				<div class='fieldBoxLarge'>
				<select name='gradSubjectsFORE' id='gradSubjectsFORE'    tip="Please select the graduation subjects if they are applicable to you. If not, select others."    validate="validateSelect"   required="true"   caption="Subjects"   onmouseover="showTipOnline('Please select the graduation subjects if they are applicable to you. If not, select others.',this);" onmouseout="hidetip();" ><option value=''>Select</option><option value='Physics(Hons)'>Physics(Hons)</option><option value='Chemistry(Hons)' >Chemistry(Hons)</option><option value='Mathematics(Hons)' >Mathematics(Hons)</option><option value='Electronics(Hons)' >Electronics(Hons)</option><option value='Bio-technology(Hons)' >Bio-technology(Hons)</option><option value='Computer Science(Hons)' >Computer Science(Hons)</option><option value='Statistics(Hons)' >Statistics(Hons)</option><option value='Life Science(Hons)' >Life Science(Hons)</option><option value='Economics(Hons)' >Economics(Hons)</option><option value='Psychology(Hons)' >Psychology(Hons)</option><option value='Public Administration(Hons)' >Public Administration(Hons)</option><option value='Microbiology(Hons)' >Microbiology(Hons)</option><option value='Others' >Others</option></select>
				<?php if(isset($gradSubjectsFORE) && $gradSubjectsFORE!=""){ ?>
			      <script>
				  var selObj = document.getElementById("gradSubjectsFORE"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $gradSubjectsFORE;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradSubjectsFORE_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label><?php echo $graduationCourseName;?> Duration in Years: </label>
				<div class='fieldBoxLarge'>
				<select style="width:134px;" name='gradDurationFORE' id='gradDurationFORE'    tip="Select the duration for your graduation course. For example, if you did a 3 years BA programme, select THREE"    validate="validateSelect"   required="true"   caption="Duration"   onmouseover="showTipOnline('Select the duration for your graduation course. For example, if you did a 3 years BA programme, select THREE',this);" onmouseout="hidetip();" ><option value='' selected>Select</option><option value='One' >One</option><option value='Two' >Two</option><option value='Three' >Three</option><option value='Four' >Four</option><option value='Five' >Five</option></select>
				<?php if(isset($gradDurationFORE) && $gradDurationFORE!=""){ ?>
			      <script>
				  var selObj = document.getElementById("gradDurationFORE"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $gradDurationFORE;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradDurationFORE_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label><?php echo $graduationCourseName;?> Mode of study: </label>
				<div class='fieldBoxLarge'>
				<select style="width:134px;" name='gradModeFORE' id='gradModeFORE'    tip="Select the mode of study for your graduation program."    validate="validateSelect"   required="true"   caption="Mode"   onmouseover="showTipOnline('Select the mode of study for your graduation program.',this);" onmouseout="hidetip();" ><option value='' selected>Select</option><option value='Full Time' >Full Time</option><option value='Part Time' >Part Time</option><option value='Correspondence' >Correspondence</option><option value='Others' >Others</option></select>
				<?php if(isset($gradModeFORE) && $gradModeFORE!=""){ ?>
			      <script>
				  var selObj = document.getElementById("gradModeFORE"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $gradModeFORE;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradModeFORE_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label><?php echo $graduationCourseName;?> University Code: </label>
				<div class='fieldBoxLarge'>
				<select style="width:134px;" name='gradCodeFORE' id='gradCodeFORE'    tip="Please select the type of university from where you did your graduation course."    validate="validateSelect"   required="true"   caption="Code"   onmouseover="showTipOnline('Please select the type of university from where you did your graduation course.',this);" onmouseout="hidetip();" ><option value='' selected>Select</option><option value='Central University' >Central University</option><option value='State University' >State University</option><option value='IIT' >IIT</option><option value='NIT' >NIT</option><option value='Private University' >Private University</option><option value='Deemed University' >Deemed University</option><option value='Foreign University' >Foreign University</option><option value='Others' >Others</option></select>
				<?php if(isset($gradCodeFORE) && $gradCodeFORE!=""){ ?>
			      <script>
				  var selObj = document.getElementById("gradCodeFORE"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $gradCodeFORE;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradCodeFORE_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label><?php echo $graduationCourseName;?> Maximum Marks  / CGPA: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gradMaxMarksFORE' id='gradMaxMarksFORE'  validate="validateStr"   required="true"   caption="Max Marks"   minlength="1"   maxlength="10"     tip="Please enter the maximum marks or maximum CGPA for your graduation course. For example if you appeared in 4 subjects, each having a maximum marks 100, then the maximum marks for all the subjects combined will be 400"   value=''   />
				<?php if(isset($gradMaxMarksFORE) && $gradMaxMarksFORE!=""){ ?>
				  <script>
				      document.getElementById("gradMaxMarksFORE").value = "<?php echo str_replace("\n", '\n', $gradMaxMarksFORE );  ?>";
				      document.getElementById("gradMaxMarksFORE").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradMaxMarksFORE_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label><?php echo $graduationCourseName;?> Marks / CGPA Obtained: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gradMarksFORE' id='gradMarksFORE'  validate="validateStr"   required="true"   caption="Marks"   minlength="1"   maxlength="10"     tip="Please enter the  marks or CGPA obtained in your graduation course. These marks or CGPA obtained will be from Maximum Marks. For example, you obtained 75 marks each out of 100 maximum marks, in 2 subjects, then your total marks obtained will be 150."   value=''   />
				<?php if(isset($gradMarksFORE) && $gradMarksFORE!=""){ ?>
				  <script>
				      document.getElementById("gradMarksFORE").value = "<?php echo str_replace("\n", '\n', $gradMarksFORE );  ?>";
				      document.getElementById("gradMarksFORE").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradMarksFORE_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label><?php echo $graduationCourseName;?> Overall Percentage: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gradPercentageFORE' id='gradPercentageFORE'  validate="validateStr"   required="true"   caption="Percentage"   minlength="1"   maxlength="4"     tip="Please enter your overall percentage in graduation"   value=''   />
				<?php if(isset($gradPercentageFORE) && $gradPercentageFORE!=""){ ?>
				  <script>
				      document.getElementById("gradPercentageFORE").value = "<?php echo str_replace("\n", '\n', $gradPercentageFORE );  ?>";
				      document.getElementById("gradPercentageFORE").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradPercentageFORE_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<h3 class="upperCase">Work experience (if applicable)</h3>
				<div class='additionalInfoLeftCol'>
				<label>Work experience (in Months) as on 30/11/2012: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='workExFORE' id='workExFORE'  validate="validateInteger"   required="true"   caption="Work Experience"   minlength="1"   maxlength="5"     tip="Enter your total work experience in number of months."   value=''   />
				<?php if(isset($workExFORE) && $workExFORE!=""){ ?>
				  <script>
				      document.getElementById("workExFORE").value = "<?php echo str_replace("\n", '\n', $workExFORE );  ?>";
				      document.getElementById("workExFORE").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'workExFORE_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<h3 class="upperCase">How did you hear about fore</h3>
				<div class='additionalInfoLeftCol'>
				<label>How did you find out about FORE: </label>
				<div class='fieldBoxLarge'>
				<select name='sourceFORE' id='sourceFORE'    tip="Please select where you heard about Fore"    validate="validateSelect"   required="true"   caption="Source"   onmouseover="showTipOnline('Please select where you heard about Fore',this);" onmouseout="hidetip();" >
					<option value='' selected>Select</option><option value='Friends/Family' >Friends/Family</option><option value='News papers' >News papers</option><option value='Magazines' >Magazines</option><option value='Social networking site' >Social networking site</option><option value='Web portals' >Web portals</option><option value='Others' >Others</option>
				</select>
				<?php if(isset($sourceFORE) && $sourceFORE!=""){ ?>
			      <script>
				  var selObj = document.getElementById("sourceFORE"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $sourceFORE;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'sourceFORE_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<h3 class="upperCase">Disclaimer</h3>
				<div class="additionalInfoLeftCol" style="width:950px">
						<label style="font-weight:normal; padding-top:0">Terms:</label>
						<div class='fieldBoxLarge' style="width:620px; color:#666666; font-style:italic">
						I declare that the particulars given are correct to the best of my knowledge and belief. If at any stage it is found that any of the information is incorrect, I will withdraw from the programme and will not claim any refund.
						<div class="spacer10 clearFix"></div>
						<div>
						<input type='checkbox' name='agreeToTermsFORE' id='agreeToTermsFORE' value='1' required="true" validate="validateChecked" caption="Please agree to the terms stated above">&nbsp;I agree to the terms stated above
		
					      <?php if(isset($agreeToTermsFORE) && $agreeToTermsFORE!=""){ ?>
						<script>
						    objCheckBoxes = document.forms["OnlineForm"].elements["agreeToTermsFORE"];
						    var countCheckBoxes = 1;
						    for(var i = 0; i < countCheckBoxes; i++){ 
							      objCheckBoxes.checked = false;
							      <?php $arr = explode(",",$agreeToTermsFORE);
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
						<div style='display:none'><div class='errorMsg' id= 'agreeToTermsFORE_error'></div></div>
		
		
						</div>
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


function validateCAT(str, caption, maxLength,minLength, allowedChars){
      str = str.replace(/\s+/g, '');
      $('catRollNumberAdditional').value = str;	
      str = str.replace(/[^\x20-\x7E]/g,'');
      if (checkHtmlTags(str)){
                return "HTML tags will be removed.";
        }
    if(str.length == '') {
        return  "Please enter the "+ caption +".";
    }else if(str.length > maxLength) {
        return "Please use a maximum of "+maxLength+" characters for "+caption;
    } else if(str.length<minLength) {
        return "The " + caption+ " must contain atleast "+ minLength +" characters.";
    } else {
        str = removeNewLineCharacters(str) ;
	var regexNum = /\d/;
	var regexLetter = /[a-zA-z]/;
	if(!regexLetter.test(str.charAt(0)) || !regexLetter.test(str.charAt(1))){
		return "Please enter correct CAT registration number";
	}
	if( isNaN (str.substring(2)) ){
		return "Please enter correct CAT registration number";
	}
        return true;
    }
}

  </script>
