<div class='formChildWrapper'>
	<div class='formSection'>
		<ul>
			<?php if($action != 'updateScore'):?>
			<li>
				<h3 class="upperCase">Additional family details:</h3>
				<div class='additionalInfoLeftCol'>
				<label>Father's Qualification: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='fatherQualificationIPE' id='fatherQualificationIPE'  validate="validateStr"   required="true"   caption="Father's Qualification"   minlength="2"   maxlength="50"     tip="Enter the educational qualification of your Father. For example:<br>- BA<br>- MA<br>- Metriculation<br>- MBBS<br>If your father does not hold any formal qualification, enter <b>NA</b>."   value=''    allowNA = 'true' />
				<?php if(isset($fatherQualificationIPE) && $fatherQualificationIPE!=""){ ?>
				  <script>
				      document.getElementById("fatherQualificationIPE").value = "<?php echo str_replace("\n", '\n', $fatherQualificationIPE );  ?>";
				      document.getElementById("fatherQualificationIPE").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'fatherQualificationIPE_error'></div></div>
				</div>
				</div>
                
                <div class='additionalInfoRightCol'>
				<label>Mother's Qualification: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='motherQualificationIPE' id='motherQualificationIPE'  validate="validateStr"   required="true"   caption="Mother's Qualification"   minlength="2"   maxlength="50"     tip="Enter the educational qualification of your Mother. For example:<br>- BA<br>- MA<br>- Metriculation<br>- MBBS<br>If your Mother does not hold any formal qualification, enter <b>NA</b>."   value=''    allowNA = 'true' />
				<?php if(isset($motherQualificationIPE) && $motherQualificationIPE!=""){ ?>
				  <script>
				      document.getElementById("motherQualificationIPE").value = "<?php echo str_replace("\n", '\n', $motherQualificationIPE );  ?>";
				      document.getElementById("motherQualificationIPE").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'motherQualificationIPE_error'></div></div>
				</div>
				</div>
			</li>
			
			<li>
				<h3 class="upperCase">Additional education details (Schooling):</h3>
				<div class='additionalInfoLeftCol'>
				<label>Class 10th medium of instruction: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='class10Medium' id='class10Medium'  validate="validateStr"   required="true"   caption="Class 10th medium of instruction"   minlength="2"   maxlength="50"     tip="Enter the medium of instruction i.e. the language in which you were taught this course. Example: For english medium, enter <b>English</b>."   value=''   />
				<?php if(isset($class10Medium) && $class10Medium!=""){ ?>
				  <script>
				      document.getElementById("class10Medium").value = "<?php echo str_replace("\n", '\n', $class10Medium );  ?>";
				      document.getElementById("class10Medium").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'class10Medium_error'></div></div>
				</div>
				</div>
                
                <div class='additionalInfoRightCol'>
				<label>Class 10th Class/Division: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='class10Division' id='class10Division'  validate="validateStr"   required="true"   caption="Class 10th Class/Division"   minlength="2"   maxlength="50"     tip="Enter Class (First class, Second class, Third Class) or Division (First Division, Second Division, Third Division) that you scored for this course. If your course didn't include Class or Division, just enter <b>NA</b>. "   value=''    allowNA = 'true' />
				<?php if(isset($class10Division) && $class10Division!=""){ ?>
				  <script>
				      document.getElementById("class10Division").value = "<?php echo str_replace("\n", '\n', $class10Division );  ?>";
				      document.getElementById("class10Division").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'class10Division_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Class 12th medium of instruction : </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='class12Medium' id='class12Medium'  validate="validateStr"   required="true"   caption="Class 12th medium of instruction "   minlength="2"   maxlength="50"     tip="Enter the medium of instruction i.e. the language in which you were taught this course. Example: For english medium, enter <b>English</b>."   value=''   />
				<?php if(isset($class12Medium) && $class12Medium!=""){ ?>
				  <script>
				      document.getElementById("class12Medium").value = "<?php echo str_replace("\n", '\n', $class12Medium );  ?>";
				      document.getElementById("class12Medium").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'class12Medium_error'></div></div>
				</div>
				</div>
                
                <div class='additionalInfoRightCol'>
				<label>Class 12th Class/Division: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='class12Division' id='class12Division'  validate="validateStr"   required="true"   caption="Class 12th Class/Division"   minlength="2"   maxlength="50"     tip="Enter Class (First class, Second class, Third Class) or Division (First Division, Second Division, Third Division) that you scored for this course. If your course didn't include Class or Division, just enter <b>NA</b>. "   value=''    allowNA = 'true' />
				<?php if(isset($class12Division) && $class12Division!=""){ ?>
				  <script>
				      document.getElementById("class12Division").value = "<?php echo str_replace("\n", '\n', $class12Division );  ?>";
				      document.getElementById("class12Division").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'class12Division_error'></div></div>
				</div>
				</div>
			</li>
			
			<?php
$graduationCourseName = 'Graduation';
$otherCourses = array();

$educationCourses = array();

if(is_array($educationDetails)) {
	foreach($educationDetails as $educationDetail) {
		if($educationDetail['value']) {
			if($educationDetail['fieldName'] == 'graduationExaminationName') {
				$graduationCourseName = $educationDetail['value'];
				$educationCourses['graduation'] = $educationDetail['value'];
			}
			else {
				for($i=1;$i<=4;$i++) {
					if($educationDetail['fieldName'] == 'graduationExaminationName_mul_'.$i) {
						$otherCourses[$i] = $educationDetail['value'];
						$educationCourses[$i] = $educationDetail['value'];
					}
				}
			}
		}
	}
}

foreach($educationCourses as $educationCourseKey => $educationCourse) {
	
	$varPrefix = $educationCourseKey == 'graduation'?'graduation':'otherCourses';
	$varSuffix = $educationCourseKey == 'graduation'?'':'_mul_'.$educationCourseKey;
	
	$mediumVarName = $varPrefix.'Medium'.$varSuffix;
	$mediumValue = ${$mediumVarName};
	
	$divisionVarName = $varPrefix.'Division'.$varSuffix;
	$divisionValue = ${$divisionVarName};
?>				
			<li>
				<?php if($educationCourseKey == 'graduation' || $educationCourseKey == '1'): ?>
					<h3 class="upperCase">Additional education details (<?php echo $educationCourseKey == 'graduation'?'Graduation':'Other'; ?>):</h3>
				<?php endif; ?>
				<div class='additionalInfoLeftCol'>
				<label><?php echo $educationCourse; ?> medium of instruction : </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='<?php echo $mediumVarName; ?>' id='<?php echo $mediumVarName; ?>'  validate="validateStr"   required="true"   caption="medium of instruction "   minlength="2"   maxlength="50"     tip="Enter the medium of instruction i.e. the language in which you were taught this course. Example: For english medium, enter <b>English</b>."   value=''   />
				<?php if(isset($mediumValue) && $mediumValue!=""){ ?>
				  <script>
				      document.getElementById("<?php echo $mediumVarName; ?>").value = "<?php echo str_replace("\n", '\n', $mediumValue );  ?>";
				      document.getElementById("<?php echo $mediumVarName; ?>").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= '<?php echo $mediumVarName; ?>_error'></div></div>
				</div>
				</div>
                
                <div class='additionalInfoRightCol'>
				<label><?php echo $educationCourse; ?> Class/Division: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='<?php echo $divisionVarName; ?>' id='<?php echo $divisionVarName; ?>'  validate="validateStr"   required="true"   caption="Class/Division"   minlength="2"   maxlength="50"     tip="Enter Class (First class, Second class, Third Class) or Division (First Division, Second Division, Third Division) that you scored for this course. If your course didn't include Class or Division, just enter <b>NA</b>. "   value=''    allowNA = 'true' />
				<?php if(isset($divisionValue) && $divisionValue!=""){ ?>
				  <script>
				      document.getElementById("<?php echo $divisionVarName; ?>").value = "<?php echo str_replace("\n", '\n', $divisionValue );  ?>";
				      document.getElementById("<?php echo $divisionVarName; ?>").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= '<?php echo $divisionVarName; ?>_error'></div></div>
				</div>
				</div>
			</li>
<?php
}
?>
			<?php endif;?>
			<li>
				<h3 class="upperCase">Qualifying Examinations:</h3>
				<div class='additionalInfoLeftCol'>
				<label>Candidate's Name (as given in the Test Application): </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='candidateName' id='candidateName'  validate="validateDisplayName"   required="true"   caption="name"   minlength="2"   maxlength="75" tip="Enter the name as it appears on your CAT/MAT/XAT/ATMA admit card."   value=''  />
				<?php
				
				$nameOfTheUser = '';
				foreach($basicInformation as $info) {
					if($info['fieldName'] == 'firstName' || $info['fieldName'] == 'middleName' || $info['fieldName'] == 'lastName') {
						$nameOfTheUser .= $info['value'].' ';
					}
				}
				
				if(!$candidateName) {
					$candidateName = $nameOfTheUser;
				}
				
				if(isset($candidateName) && $candidateName!=""){ ?>
				  <script>
				      document.getElementById("candidateName").value = "<?php echo str_replace("\n", '\n', $candidateName );  ?>";
				      document.getElementById("candidateName").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'candidateName_error'></div></div>
				</div>
				</div>
			</li>
			
			<li>
				<div class='additionalInfoLeftCol'>
				<label>CAT Registration/Roll number: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='catRollNumberIPE' id='catRollNumberIPE'  validate="validateStr"   required="true"   caption="CAT Registration/Roll number"   minlength="2"   maxlength="50" tip="Enter the CAT roll number as it appears on your CAT admit card. If you haven't taken CAT, enter <b>NA</b>."   value=''    allowNA = 'true' />
				<?php if(isset($catRollNumberIPE) && $catRollNumberIPE!=""){ ?>
				  <script>
				      document.getElementById("catRollNumberIPE").value = "<?php echo str_replace("\n", '\n', $catRollNumberIPE );  ?>";
				      document.getElementById("catRollNumberIPE").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'catRollNumberIPE_error'></div></div>
				</div>
				</div>
                
                <div class='additionalInfoRightCol'>
				<label>MAT Registration/Roll number: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='matRollNumberIPE' id='matRollNumberIPE'  validate="validateStr"   required="true"   caption="MAT Registration/Roll number"   minlength="2"   maxlength="50"     tip="Enter the MAT roll number as it appears on your MAT admit card. If you haven't taken MAT, enter <b>NA</b>."   value=''    allowNA = 'true' />
				<?php if(isset($matRollNumberIPE) && $matRollNumberIPE!=""){ ?>
				  <script>
				      document.getElementById("matRollNumberIPE").value = "<?php echo str_replace("\n", '\n', $matRollNumberIPE );  ?>";
				      document.getElementById("matRollNumberIPE").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'matRollNumberIPE_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>XAT Registration/Roll number: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='xatRollNumberIPE' id='xatRollNumberIPE'  validate="validateStr"   required="true"   caption="XAT Registration/Roll number"   minlength="2"   maxlength="50"     tip="Enter the XAT roll number as it appears on your XAT admit card. If you haven't taken XAT, enter <b>NA</b>."   value=''    allowNA = 'true' />
				<?php if(isset($xatRollNumberIPE) && $xatRollNumberIPE!=""){ ?>
				  <script>
				      document.getElementById("xatRollNumberIPE").value = "<?php echo str_replace("\n", '\n', $xatRollNumberIPE );  ?>";
				      document.getElementById("xatRollNumberIPE").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'xatRollNumberIPE_error'></div></div>
				</div>
				</div>
                <div class='additionalInfoRightCol'>
				<label>ATMA Registration/Roll number: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='atmaRollNumberIPE' id='atmaRollNumberIPE'  validate="validateStr"   required="true"   caption="ATMA Registration/Roll number"   minlength="2"   maxlength="50" tip="Enter the ATMA roll number as it appears on your ATMA admit card. If you haven't taken ATMA, enter <b>NA</b>."   value=''    allowNA = 'true' />
				<?php if(isset($atmaRollNumberIPE) && $atmaRollNumberIPE!=""){ ?>
				  <script>
				      document.getElementById("atmaRollNumberIPE").value = "<?php echo str_replace("\n", '\n', $atmaRollNumberIPE );  ?>";
				      document.getElementById("atmaRollNumberIPE").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'atmaRollNumberIPE_error'></div></div>
				</div>
				</div>
			</li>

			
<?php if($action != 'updateScore'):?>
			<?php
			$numArray = array(1=>'1st',2=>'2nd',3=>'3rd',4=>'4th',5=>'5th',6=>'6th');
			for($i = 1;$i<=6;$i++) {
				$prefVal = ${'progPrfe'.$i};
			?>
				<?php if($i%2 == 1): ?><li><?php if($i == 1) echo '<h3 class="upperCase">Program Preferences <span style="color:#666; font-size:12px; font-weight:normal; text-transform:none;">(Note: Please indicate your preferences for IPE programmes)</span>:</h3>'; ?><div class='additionalInfoLeftCol'>
				<?php else: ?> <div class='additionalInfoRightCol'>
				<?php endif; ?>
				
				<label><?php echo $numArray[$i]; ?> Preference: </label>
				<div class='fieldBoxLarge'>
				<select class="selectboxLarge2" name='progPrfe<?php echo $i; ?>' id='progPrfe<?php echo $i; ?>'    tip="Enter your <?php echo $numArray[$i]; ?> program preference i.e. which program do you wish to be considered for as your <?php echo $numArray[$i]; ?> choice."    validate="validateSelect"   required="true"   caption="<?php echo $numArray[$i]; ?> Preference"  blurMethod="checkProgramPrefConflict('<?php echo $i; ?>');" onmouseover="showTipOnline('Enter your <?php echo $numArray[$i]; ?> program preference i.e. which program do you wish to be considered for as your <?php echo $numArray[$i]; ?> choice.',this);" onmouseout="hidetip();" >
					<option value="">Select</option>
					<option value='PGDM'>PGDM</option>
					<option value='PGDM-RM'>PGDM-RM</option>
					<option value='PGDM-BIF' >PGDM-BIF</option>
					<option value='PGDM-IB' >PGDM-IB</option>
					<option value='PGDM-BT' >PGDM-BT</option>
					<option value='Exe. PGDM' >Exe. PGDM</option>
				</select>
				<?php if(isset($prefVal) && $prefVal!=""){ ?>
			      <script>
				  var selObj = document.getElementById("progPrfe<?php echo $i; ?>"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $prefVal;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'progPrfe<?php echo $i; ?>_error'></div></div>
				</div>
				</div>
				<?php if($i%2 == 0): ?></li>
				<?php endif; ?>
			<?php
			}
			?>
                
		
			<li>
				<h3 class="upperCase">HOW DID YOU COME TO KNOW ABOUT IPE? (Optional)</h3>
				<div class='additionalInfoLeftCol'>
				<label>Newspaper: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='heardInNewspaper' id='heardInNewspaper'         tip="Enter the name of newspaper where you came to know about IPE. If it doesn't apply to you, leave this field blank."   value=''   />
				<?php if(isset($heardInNewspaper) && $heardInNewspaper!=""){ ?>
				  <script>
				      document.getElementById("heardInNewspaper").value = "<?php echo str_replace("\n", '\n', $heardInNewspaper );  ?>";
				      document.getElementById("heardInNewspaper").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'heardInNewspaper_error'></div></div>
				</div>
				</div>
                
                <div class='additionalInfoRightCol'>
				<label>Business Magazine: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='heardInBusinessMagazine' id='heardInBusinessMagazine'         tip="Enter the name of business magazine where you came to know about IPE. If it doesn't apply to you, leave this field blank."   value=''   />
				<?php if(isset($heardInBusinessMagazine) && $heardInBusinessMagazine!=""){ ?>
				  <script>
				      document.getElementById("heardInBusinessMagazine").value = "<?php echo str_replace("\n", '\n', $heardInBusinessMagazine );  ?>";
				      document.getElementById("heardInBusinessMagazine").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'heardInBusinessMagazine_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Coaching centre: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='heardInCoachingCentre' id='heardInCoachingCentre'         tip="Enter the name of coaching centre where you came to know about IPE. If it doesn't apply to you, leave this field blank."   value=''   />
				<?php if(isset($heardInCoachingCentre) && $heardInCoachingCentre!=""){ ?>
				  <script>
				      document.getElementById("heardInCoachingCentre").value = "<?php echo str_replace("\n", '\n', $heardInCoachingCentre );  ?>";
				      document.getElementById("heardInCoachingCentre").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'heardInCoachingCentre_error'></div></div>
				</div>
				</div>
                
                <div class='additionalInfoRightCol'>
				<label>IPE Almuni: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='heardFromIPEAlmuni' id='heardFromIPEAlmuni'         tip="Enter the name of IPE Almuni from whom you came to know about IPE. If it doesn't apply to you, leave this field blank."   value=''   />
				<?php if(isset($heardFromIPEAlmuni) && $heardFromIPEAlmuni!=""){ ?>
				  <script>
				      document.getElementById("heardFromIPEAlmuni").value = "<?php echo str_replace("\n", '\n', $heardFromIPEAlmuni );  ?>";
				      document.getElementById("heardFromIPEAlmuni").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'heardFromIPEAlmuni_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Friends: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='heardFromFriends' id='heardFromFriends'         tip="Enter the name of friend where you came to know about IPE. If it doesn't apply to you, leave this field blank."   value=''   />
				<?php if(isset($heardFromFriends) && $heardFromFriends!=""){ ?>
				  <script>
				      document.getElementById("heardFromFriends").value = "<?php echo str_replace("\n", '\n', $heardFromFriends );  ?>";
				      document.getElementById("heardFromFriends").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'heardFromFriends_error'></div></div>
				</div>
				</div>
                
                <div class='additionalInfoRightCol'>
				<label>Any other: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='heardFromAnyOther' id='heardFromAnyOther'         tip="Enter the name of any other source where you came to know about IPE. If it doesn't apply to you, leave this field blank."   value=''   />
				<?php if(isset($heardFromAnyOther) && $heardFromAnyOther!=""){ ?>
				  <script>
				      document.getElementById("heardFromAnyOther").value = "<?php echo str_replace("\n", '\n', $heardFromAnyOther );  ?>";
				      document.getElementById("heardFromAnyOther").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'heardFromAnyOther_error'></div></div>
				</div>
				</div>
			</li>

<?php if(is_array($gdpiLocations)): ?>
	<li>
		<label style="font-weight:normal">Preferred GD/PI location: </label>
		<div class='fieldBoxLarge'>
        <select class="selectboxLarge2" name='preferredGDPILocation' id='preferredGDPILocation' onmouseover="showTipOnline('Select your preferred GD/PI location.',this);" onmouseout='hidetip();'  validate="validateStr"  minlength="1"   maxlength="1500"  required="true" caption="Preferred GD/PI location">
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

			<li>
		<label style="font-weight:normal; padding-top:0">Terms:</label>
		<div class='float_L' style="width:620px; color:#666666; font-style:italic">
		I,  <?php echo $nameOfTheUser; ?> affirm that the information furnished above is correct to the best of my knowledge and belief, and that I will accept as final and binding, the decision of Institute of Public Enterprise regarding my admission to the Post Graduate Programme. If any information provided by me is found to be false or incorrect at a later date, I will be held solely responsible for the legal and other Consequences.
		
		<div class="spacer10 clearFix"></div>
       	<div >
        	<input type='checkbox' name='agreeToTermsIPE[]' id='agreeToTermsIPE0' value='1' required="true" validate="validateChecked" caption="Please agree to the terms stated above"></input>&nbsp;&nbsp;
			I agree to the terms stated above
			<?php if(isset($agreeToTermsIPE) && $agreeToTermsIPE!=""){ ?>
				<script>
		    
			objCheckBoxes = document.getElementsByName("agreeToTermsIPE[]");
		    var countCheckBoxes = objCheckBoxes.length;
			
		    for(var i = 0; i < countCheckBoxes; i++){
			      objCheckBoxes[i].checked = false;

			      <?php $arr = explode(",",$agreeToTermsIPE);
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
		<div style='display:none'><div class='errorMsg' id= 'agreeToTermsIPE0_error'></div></div>
		</div>
	</div>
	</li>
        <?php endif;?>
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
  
  function checkProgramPrefConflict(index) {
	var selectedSB = document.getElementById('progPrfe'+index);
	var selectedSBVal = selectedSB.options[selectedSB.selectedIndex].value;
	
	if(selectedSBVal) {
		for(var i=1;i<=6;i++){
			if(i != index) {
				var currentSB = document.getElementById('progPrfe'+i);
				var currentSBVal = currentSB.options[currentSB.selectedIndex].value;
				
				if(currentSBVal == selectedSBVal) {
					selectedSB.selectedIndex = 0;
					$('progPrfe'+index+'_error').innerHTML = 'Same program cannot have more than one preference';
					$('progPrfe'+index+'_error').parentNode.style.display = '';
					return false;
				}
			}
		}
	}
	
	return true;
  }
  </script>
