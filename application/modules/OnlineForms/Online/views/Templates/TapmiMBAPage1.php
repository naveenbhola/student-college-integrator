<div class="formChildWrapper">
	<div class="formSection">
    	<ul>
    <?php if($action != 'updateScore'):?>	
<li>
<div class="additionalInfoLeftCol">
<label>Mother Tongue: </label>
<div class='fieldBoxLarge'><input class="textboxLarge" type='text' name='motherTongue' id='motherTongue'  validate="validateStr"   required="true"   caption="mother tongue"   minlength="1"   maxlength="50"  tip="Pelase enter your mother tongue (also known as native langues). It's is usually the language that you have grown up speaking from early childhood."   value=''  />
<?php if(isset($motherTongue) && $motherTongue!=""){ ?>
		<script>
		    document.getElementById("motherTongue").value = "<?php echo str_replace("\n", '\n', $motherTongue );  ?>";
		    document.getElementById("motherTongue").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'motherTongue_error'></div></div>
</div>
</div>
</li>
<?php endif;?>
<li>
<div class="additionalInfoLeftCol">
<label>CAT Roll Number: </label>
<div class='fieldBoxLarge'><input type='text' name='catRollNumberAdditional' id='catRollNumberAdditional'  validate="validateStr"   required="true"   caption="roll number"   minlength="1"   maxlength="20"     tip="Mention your roll number for the exam."   value='' class="textboxLarge" />
<?php if(isset($catRollNumberAdditional) && $catRollNumberAdditional!=""){ ?>
		<script>
		    document.getElementById("catRollNumberAdditional").value = "<?php echo str_replace("\n", '\n', $catRollNumberAdditional );  ?>";
		    document.getElementById("catRollNumberAdditional").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'catRollNumberAdditional_error'></div></div>
</div>
</div>
<div class="additionalInfoRightCol">
<label>MAT Roll Number: </label>
<div class='fieldBoxLarge'><input type='text' name='matRollNumberAdditional' id='matRollNumberAdditional'  validate="validateStr"   required="true"   caption="roll number"   minlength="1"   maxlength="20"     tip="Mention your roll number for the exam."   value='' class="textboxLarge" />
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

<li>
<div class="additionalInfoLeftCol">
<label>GMAT Roll Number: </label>
<div class='fieldBoxLarge'><input type='text' name='gmatRollNumberAdditional' id='gmatRollNumberAdditional'  validate="validateStr"   required="true"   caption="roll number"   minlength="1"   maxlength="20"     tip="Mention your roll number for the exam."   value='' class="textboxLarge" />
<?php if(isset($gmatRollNumberAdditional) && $gmatRollNumberAdditional!=""){ ?>
		<script>
		    document.getElementById("gmatRollNumberAdditional").value = "<?php echo str_replace("\n", '\n', $gmatRollNumberAdditional );  ?>";
		    document.getElementById("gmatRollNumberAdditional").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'gmatRollNumberAdditional_error'></div></div>
</div>
</div>
<div class="additionalInfoRightCol">
<label>GMAT Score: </label>
<div class='fieldBoxLarge'><input type='text' name='gmatScoreAdditional' id='gmatScoreAdditional'  validate="validateFloat" allowNA="true"  required="true"   caption="score"   minlength="1"   maxlength="5"     tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter NA."   value='' class="textboxLarge"  />
<?php if(isset($gmatScoreAdditional) && $gmatScoreAdditional!=""){ ?>
		<script>
		    document.getElementById("gmatScoreAdditional").value = "<?php echo str_replace("\n", '\n', $gmatScoreAdditional );  ?>";
		    document.getElementById("gmatScoreAdditional").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'gmatScoreAdditional_error'></div></div>
</div>
</div>
</li>

<li>
<div class="additionalInfoLeftCol">
<label>GRE registration Number: </label>
<div class='fieldBoxLarge'><input class="textboxLarge" type='text' name='greRegistrationNumber' id='greRegistrationNumber'  validate="validateStr" allowNA="true"  required="true"   caption="GRE registration number"   minlength="1"   maxlength="20"     tip="Please enter your GRE registration number. If you have not appeared for this examination enter NA."   value=''  />
<?php if(isset($greRegistrationNumber) && $greRegistrationNumber!=""){ ?>
		<script>
		    document.getElementById("greRegistrationNumber").value = "<?php echo str_replace("\n", '\n', $greRegistrationNumber );  ?>";
		    document.getElementById("greRegistrationNumber").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'greRegistrationNumber_error'></div></div>
</div>
</div>

<div class="additionalInfoRightCol">
	<label>GRE score: </label>
    <div class='fieldBoxLarge'><input class="textboxLarge" type='text' name='greScore' id='greScore'  validate="validateInteger"   required="true" allowNA="true"  caption="GRE score"   minlength="1"   maxlength="4"     tip="Please enter your GRE score. If you have not appeared for this examination enter NA."   value=''  />
<?php if(isset($greScore) && $greScore!=""){ ?>
		<script>
		    document.getElementById("greScore").value = "<?php echo str_replace("\n", '\n', $greScore );  ?>";
		    document.getElementById("greScore").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'greScore_error'></div></div>
</div>
</div>
</li>

<li>
<div class="additionalInfoLeftCol">
<label>XAT registration Number: </label>
<div class='fieldBoxLarge'><input class="textboxLarge" type='text' name='xatRollNumber' id='xatRollNumber'  validate="validateStr"   required="true"   caption="XAT registration number"   minlength="1"   maxlength="20"     tip="Please enter your XAT registration number. If you have not appeared for this examination enter NA."   value=''  />
<?php if(isset($xatRollNumber) && $xatRollNumber!=""){ ?>
		<script>
		    document.getElementById("xatRollNumber").value = "<?php echo str_replace("\n", '\n', $xatRollNumber );  ?>";
		    document.getElementById("xatRollNumber").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'xatRollNumber_error'></div></div>
</div>
</div>
</li>
<?php if($action != 'updateScore'):?>
<li>
<div class="additionalInfoLeftCol">
<label>Passport Number: </label>
<div class='fieldBoxLarge'><input class="textboxLarge" type='text' name='passportNumber' id='passportNumber'  validate="validateStr"   required="true"   caption="passport number"   minlength="1"   maxlength="20"     tip="Please enter your passport number. If you do not have a passport or have applied for one, enter NA."   value=''  />
<?php if(isset($passportNumber) && $passportNumber!=""){ ?>
		<script>
		    document.getElementById("passportNumber").value = "<?php echo str_replace("\n", '\n', $passportNumber );  ?>";
		    document.getElementById("passportNumber").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'passportNumber_error'></div></div>
</div>
</div>
</li>

<li>
<div class="additionalInfoLeftCol">
<label>Issued by: </label>
<div class='fieldBoxLarge'>
<input class="textboxLarge" type='text' name='passportIssuedBy' id='passportIssuedBy'  validate="validateStr"   required="true"   caption="issued by"   minlength="1"   maxlength="50"     tip="Please enter name of passport office that issued your passport. Refer you passport to see the name of issuing office. If you do not have a passport or have applied for one, enter NA."   value=''  />
<?php if(isset($passportIssuedBy) && $passportIssuedBy!=""){ ?>
		<script>
		    document.getElementById("passportIssuedBy").value = "<?php echo str_replace("\n", '\n', $passportIssuedBy );  ?>";
		    document.getElementById("passportIssuedBy").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'passportIssuedBy_error'></div></div>
</div>
</div>

<div class="additionalInfoRightCol">
<label>Date of Expiry: </label>
<div class='fieldBoxLarge'>
<input type='text' name='passportDateOfExpiry' id='passportDateOfExpiry' readonly validate="validateStr"   required="true"   caption="date of expiry"   minlength="1"   maxlength="12" tip="Please enter the date of expiry of your passport. If you do not have a passport or have applied for one, enter NA."    onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('passportDateOfExpiry'),'passportDateOfExpiry_dateImg','dd/MM/yyyy');" />&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='passportDateOfExpiry_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('passportDateOfExpiry'),'passportDateOfExpiry_dateImg','dd/MM/yyyy'); return false;" />
<?php if(isset($passportDateOfExpiry) && $passportDateOfExpiry!=""){ ?>
		<script>
		    document.getElementById("passportDateOfExpiry").value = "<?php echo str_replace("\n", '\n', $passportDateOfExpiry );  ?>";
		    document.getElementById("passportDateOfExpiry").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'passportDateOfExpiry_error'></div></div>
</div>
</div>
</li>

<?php
$nationality = 'INDIAN';
foreach($basicInformation as $basicInformationField) {
	if($basicInformationField['fieldName'] == 'nationality') {
		$nationality = $basicInformationField['value'];
	}
}

if($nationality != 'INDIAN') {
?>

<li>
<div class="additionalInfoLeftCol">
<label>Are you a Person of Indian Origin (with valid PIO card)?: </label><div class=''><input type='radio' name='personOfIndianOrigin' id='personOfIndianOrigin0'  onclick="togglePIO('yes')" value='Yes' ></input>Yes&nbsp;&nbsp;<input type='radio' name='personOfIndianOrigin' id='personOfIndianOrigin1'   value='No' checked='checked' onclick="togglePIO('no')" ></input>No&nbsp;&nbsp;
<?php if(isset($personOfIndianOrigin) && $personOfIndianOrigin!=""){ ?>
		<script>
		    radioObj = document.forms["OnlineForm"].elements["personOfIndianOrigin"];
		    var radioLength = radioObj.length;
		    for(var i = 0; i < radioLength; i++) {
			    radioObj[i].checked = false;
			    if(radioObj[i].value == "<?php echo $personOfIndianOrigin;?>") {
				    radioObj[i].checked = true;
			    }
		    }
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'personOfIndianOrigin_error'></div></div>
</div>
</div>
</li>

</ul>
<ul id='pio_details' style='display:none;'>
<li>
		<h3 class="upperCase">PIO Card Details:</h3>
        <div class="additionalInfoLeftCol">
		<label>PIO Card No.: </label>
        <div class='fieldBoxLarge'><input type='text' name='pioCardNumber' id='pioCardNumber' validate="validateStr"  required="true" minlength="1" maxlength="20" caption="PIO card number"      tip="Enter your POI card number"   value=''  />
<?php if(isset($pioCardNumber) && $pioCardNumber!=""){ ?>
		<script>
		    document.getElementById("pioCardNumber").value = "<?php echo str_replace("\n", '\n', $pioCardNumber );  ?>";
		    document.getElementById("pioCardNumber").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'pioCardNumber_error'></div></div>
</div>
</div>

<div class="additionalInfoRightCol">
<label>Issuing Authority: </label><div class='fieldBoxLarge'><input type='text' name='pioIssuingAuthority' id='pioIssuingAuthority'   validate="validateStr"  required="true" minlength="1" maxlength="50" caption="issuing authority"        tip="Please enter name of POI office that issued your card. Refer you POI card to see the name of issuing office."   value=''  />
<?php if(isset($pioIssuingAuthority) && $pioIssuingAuthority!=""){ ?>
		<script>
		    document.getElementById("pioIssuingAuthority").value = "<?php echo str_replace("\n", '\n', $pioIssuingAuthority );  ?>";
		    document.getElementById("pioIssuingAuthority").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'pioIssuingAuthority_error'></div></div>
</div>
</div>
</li>

<li>
<div class="additionalInfoLeftCol">
<label>Date of Issue: </label>
<div class='fieldBoxLarge'><input type='text' name='pioDateOfIssue' id='pioDateOfIssue' readonly maxlength='10'   validate="validateStr"  required="true" minlength="1" caption="date of issue"        tip="Please enter the date of issue of your POI card. Refer your POI card for details of this."    onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('pioDateOfIssue'),'pioDateOfIssue_dateImg','dd/MM/yyyy');" />&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='pioDateOfIssue_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('pioDateOfIssue'),'pioDateOfIssue_dateImg','dd/MM/yyyy'); return false;" />
<?php if(isset($pioDateOfIssue) && $pioDateOfIssue!=""){ ?>
		<script>
		    document.getElementById("pioDateOfIssue").value = "<?php echo str_replace("\n", '\n', $pioDateOfIssue );  ?>";
		    document.getElementById("pioDateOfIssue").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'pioDateOfIssue_error'></div></div>
</div>
</div>
<div class="additionalInfoRightCol">
<label>Date of Expiry: </label>
<div class='fieldBoxLarge'><input type='text' name='pioDateOfExpiry' id='pioDateOfExpiry' readonly maxlength='10'   validate="validateStr"  required="true" minlength="1"  caption="date of expiry"        tip="Please enter the date of expiry of your passport. Refer your POI card for details of this."    onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('pioDateOfExpiry'),'pioDateOfExpiry_dateImg','dd/MM/yyyy');" />&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='pioDateOfExpiry_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('pioDateOfExpiry'),'pioDateOfExpiry_dateImg','dd/MM/yyyy'); return false;" />
<?php if(isset($pioDateOfExpiry) && $pioDateOfExpiry!=""){ ?>
		<script>
		    document.getElementById("pioDateOfExpiry").value = "<?php echo str_replace("\n", '\n', $pioDateOfExpiry );  ?>";
		    document.getElementById("pioDateOfExpiry").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'pioDateOfExpiry_error'></div></div>
</div>
</div>
</li>

</ul>
<ul>

<li>
<div class="additionalInfoLeftCol" style="width:790px">
<label>Address in India: </label><div style="width:380px; float:left"><textarea name='addressInIndia' id='addressInIndia'  validate="validateStr"   required="true"   caption="address in India"   minlength="1"   maxlength="200"     tip="Current address in india"   ></textarea>
<?php if(isset($addressInIndia) && $addressInIndia!=""){ ?>
		<script>
		    document.getElementById("addressInIndia").value = "<?php echo str_replace("\n", '\n', $addressInIndia );  ?>";
		    document.getElementById("addressInIndia").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'addressInIndia_error'></div></div>
</div>
</div>
</li>

<li>
<div class="additionalInfoLeftCol">
<label>Currently in India since: </label>
<div class='fieldBoxLarge'><input type='text' name='currentCountry' id='currentCountry'  validate="validateInteger"   required="true"   caption="current country"   minlength="4"   maxlength="4" value='' class="textboxLarge" />
<?php if(isset($currentCountry) && $currentCountry!=""){ ?>
		<script>
		    document.getElementById("currentCountry").value = "<?php echo str_replace("\n", '\n', $currentCountry );  ?>";
		    document.getElementById("currentCountry").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'currentCountry_error'></div></div>
</div>
</div>
</li>

<li>
<div class="additionalInfoLeftCol">
<label>Visa number: </label>
<div class='fieldBoxLarge'><input type='text' name='visaNumber' id='visaNumber'  validate="validateStr"   required="true"   caption="visa number"   minlength="1"   maxlength="20"  value='' class="textboxLarge" />
<?php if(isset($visaNumber) && $visaNumber!=""){ ?>
		<script>
		    document.getElementById("visaNumber").value = "<?php echo str_replace("\n", '\n', $visaNumber );  ?>";
		    document.getElementById("visaNumber").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'visaNumber_error'></div></div>
</div>
</div>
<div class="additionalInfoRightCol">
<label>Valid Till: </label>
<div class='fieldBoxLarge'><input type='text' name='visaValidTill' id='visaValidTill' readonly  validate="validateStr"   required="true"   caption="visa valid till"   minlength="1"   maxlength="12"       onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('visaValidTill'),'visaValidTill_dateImg','dd/MM/yyyy');" />&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='visaValidTill_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('visaValidTill'),'visaValidTill_dateImg','dd/MM/yyyy'); return false;" />
<?php if(isset($visaValidTill) && $visaValidTill!=""){ ?>
		<script>
		    document.getElementById("visaValidTill").value = "<?php echo str_replace("\n", '\n', $visaValidTill );  ?>";
		    document.getElementById("visaValidTill").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'visaValidTill_error'></div></div>
</div>
</div>
</li>

<?php
}
?>

<li>
<div class="additionalInfoLeftCol">
<label>Father's email address: </label>
<div class='fieldBoxLarge'><input type='text' name='fatherEmailAddress' id='fatherEmailAddress'  validate="validateEmail"   required="true"   caption="father's email address"   minlength="2"   maxlength="200"     tip="Please enter your father's email address"   value='' class="textboxLarge" />
<?php if(isset($fatherEmailAddress) && $fatherEmailAddress!=""){ ?>
		<script>
		    document.getElementById("fatherEmailAddress").value = "<?php echo str_replace("\n", '\n', $fatherEmailAddress );  ?>";
		    document.getElementById("fatherEmailAddress").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'fatherEmailAddress_error'></div></div>
</div>
</div>
</li>

<li>
<div class="additionalInfoLeftCol">
<label>Class 10th aggregate marks: </label>
<div class='fieldBoxLarge'><input type='text' name='class10AggregateMarks' id='class10AggregateMarks'  validate="validateFloat"   required="true"   caption="class 10 aggregate marks"   minlength="1"   maxlength="5"     tip="Enter the aggregate marks for class 10th. Refer your marksheet for total aggregate marks."   value='' class="textboxLarge" />
<?php if(isset($class10AggregateMarks) && $class10AggregateMarks!=""){ ?>
		<script>
		    document.getElementById("class10AggregateMarks").value = "<?php echo str_replace("\n", '\n', $class10AggregateMarks );  ?>";
		    document.getElementById("class10AggregateMarks").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'class10AggregateMarks_error'></div></div>
</div>
</div>

<div class="additionalInfoRightCol">
<label>Class 12th aggregate marks: </label>
<div class='fieldBoxLarge'><input type='text' name='class12AggregateMarks' id='class12AggregateMarks'  validate="validateFloat"   required="true"   caption="class 12 aggregate marks"   minlength="1"   maxlength="5"     tip="Enter the aggregate marks for class 12th. Refer your marksheet for total aggregate marks."   value='' class="textboxLarge" />
<?php if(isset($class12AggregateMarks) && $class12AggregateMarks!=""){ ?>
		<script>
		    document.getElementById("class12AggregateMarks").value = "<?php echo str_replace("\n", '\n', $class12AggregateMarks );  ?>";
		    document.getElementById("class12AggregateMarks").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'class12AggregateMarks_error'></div></div>
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
	
	$modeVarName = $varPrefix.'CourseMode'.$varSuffix;
	$modeValue = ${$modeVarName};
	
	$durationVarName = $varPrefix.'CourseDuration'.$varSuffix;
	$durationValue = ${$durationVarName};
	
	$streamVarName = $varPrefix.'CourseStream'.$varSuffix;
	$streamValue = ${$streamVarName};
	
	$statusVarName = $varPrefix.'CourseStatus'.$varSuffix;
	$statusValue = ${$statusVarName};
	
	$marksVarName = $varPrefix.'AggregateMarks'.$varSuffix;
	$marksValue = ${$marksVarName};
?>

<li>
<h3 class="upperCase"><?php echo $educationCourse; ?> Details:</h3>
<div class="additionalInfoLeftCol" style="width:790px">
<label>Mode: </label>
<div class=''>
<input type='radio' name='<?php echo $modeVarName; ?>' id='<?php echo $modeVarName; ?>0'   value='Full Time'  checked />Full Time&nbsp;&nbsp;<input type='radio' name='<?php echo $modeVarName; ?>' id='<?php echo $modeVarName; ?>1'   value='Distance'   />Distance&nbsp;&nbsp;<input type='radio' name='<?php echo $modeVarName; ?>' id='<?php echo $modeVarName; ?>2'   value='Others'   />Others&nbsp;&nbsp;
<?php if(isset($modeValue) && $modeValue!=""){ ?>
		<script>
		    radioObj = document.getElementsByName("<?php echo $modeVarName; ?>");
		    var radioLength = radioObj.length;
		    for(var i = 0; i < radioLength; i++) {
			    radioObj[i].checked = false;
			    if(radioObj[i].value == "<?php echo $modeValue;?>") {
				    radioObj[i].checked = true;
			    }
		    }
		</script>
	      <?php } ?>
	<div style='display:none'><div class='errorMsg' id= '<?php echo $modeVarName; ?>_error'></div></div>
</div>
</div>
</li>

<li>
<div class="additionalInfoLeftCol">
<label>Duration (Years): </label><div class=''><select name='<?php echo $durationVarName; ?>' id='<?php echo $durationVarName; ?>'    tip="Enter the total duration for your course. For example, B.A. (Hons) is a 3 years course and BTECH is a 4 years course."    validate="validateInteger"   required="true" minlength="1" maxlength="2"  caption="course duration" ><option value='1' selected>1</option><option value='2' >2</option><option value='3' >3</option><option value='4' >4</option><option value='5' >5</option></select>
<?php if(isset($durationValue) && $durationValue!=""){ ?>
		<script>
		    var selObj = document.getElementById("<?php echo $durationVarName; ?>"); 
		    var A= selObj.options, L= A.length;
		    while(L){
			if (A[--L].value== "<?php echo $durationValue;?>"){
			    selObj.selectedIndex= L;
			    L= 0;
			}
		    }
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= '<?php echo $durationVarName; ?>_error'></div></div>
</div>
</div>
</li>
<li>
<div class="additionalInfoLeftCol">
<label>Stream: </label>
<div class='fieldBoxLarge'><input type='text' name='<?php echo $streamVarName; ?>' id='<?php echo $streamVarName; ?>'  validate="validateStr"   required="true"   caption="stream"   minlength="1"   maxlength="50"     tip="Enter education stream or specialization. For example, if you did B.A. Honors in Economics, your stream will be Economics, If you did BTECH in Mechanical Engineering, you stream will be Mechanical."   value='' class="textboxLarge" />
<?php if(isset($streamValue) && $streamValue!=""){ ?>
		<script>
		    document.getElementById("<?php echo $streamVarName; ?>").value = "<?php echo str_replace("\n", '\n', $streamValue );  ?>";
		    document.getElementById("<?php echo $streamVarName; ?>").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= '<?php echo $streamVarName; ?>_error'></div></div>
</div>
</div>
</li>

<li>
<div class="additionalInfoLeftCol" style="width:790px">
<label>Status: </label><div class=''><input type='radio' name='<?php echo $statusVarName; ?>' id='<?php echo $statusVarName; ?>0'   value='Completed'  checked ></input>Completed&nbsp;&nbsp;<input type='radio' name='<?php echo $statusVarName; ?>' id='<?php echo $statusVarName; ?>1'   value='Not Completed'   ></input>Not Completed&nbsp;&nbsp;
<?php if(isset($statusValue) && $statusValue!=""){ ?>
		<script>
		    radioObj = document.getElementsByName("<?php echo $statusVarName; ?>");
		    var radioLength = radioObj.length;
		    for(var i = 0; i < radioLength; i++) {
			    radioObj[i].checked = false;
			    if(radioObj[i].value == "<?php echo $statusValue;?>") {
				    radioObj[i].checked = true;
			    }
		    }
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= '<?php echo $statusVarName; ?>_error'></div></div>
</div>
</div>
</li>

<li>
<div class="additionalInfoLeftCol">
<label>Aggregate marks: </label>
<div class='fieldBoxLarge'><input type='text' name='<?php echo $marksVarName; ?>' id='<?php echo $marksVarName; ?>'  validate="validateFloat"   required="true"   caption="aggregate marks"   minlength="1"   maxlength="7" value='' class="textboxLarge" />
<?php if(isset($marksValue) && $marksValue!=""){ ?>
		<script>
		    document.getElementById("<?php echo $marksVarName; ?>").value = "<?php echo str_replace("\n", '\n', $marksValue );  ?>";
		    document.getElementById("<?php echo $marksVarName; ?>").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= '<?php echo $marksVarName; ?>_error'></div></div>
</div>
</div>
</li>

<?php
}
?>

<li>
<div class="additionalInfoLeftCol" style="width:890px">
<label>Extra-Curricular Activities (If any): </label><div class=''><textarea style="width:550px" name='extraCurricular' id='extraCurricular'         tip="Enter here if you took part in any extra curricular activities. Eg: Listening to music, Western dance, Playing cricket. List all the extra curricular activities seperated by a comma. If you do not have any extra curricular activity, enter NA"   ></textarea>
<?php if(isset($extraCurricular) && $extraCurricular!=""){ ?>
		<script>
		    document.getElementById("extraCurricular").value = "<?php echo str_replace("\n", '\n', $extraCurricular );  ?>";
		    document.getElementById("extraCurricular").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'extraCurricular_error'></div></div>
</div>
</div>
</li>

<li>
<div class="additionalInfoLeftCol" style="width:890px">
<label>Achievements(Iy any): </label><div class=''><textarea style="width:550px" name='achievementsTapmi' id='achievementsTapmi'         tip="Briefly mention any achievements in you life that you would like to share. These could be acedemic as well as non-acedemic achievments. If you do not wish to state anything, just enter NA."   ></textarea>
<?php if(isset($achievementsTapmi) && $achievementsTapmi!=""){ ?>
		<script>
		    document.getElementById("achievementsTapmi").value = "<?php echo str_replace("\n", '\n', $achievementsTapmi );  ?>";
		    document.getElementById("achievementsTapmi").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'achievementsTapmi_error'></div></div>
</div>
</div>
</li>

<li>
<div class="additionalInfoLeftCol" style="width:890px">
<label>Any other relevant information you think is necessary to be conveyed: </label>
<div class=''><textarea style="width:550px" name='otherRelevantInfo' id='otherRelevantInfo' ></textarea>
<?php if(isset($otherRelevantInfo) && $otherRelevantInfo!=""){ ?>
		<script>
		    document.getElementById("otherRelevantInfo").value = "<?php echo str_replace("\n", '\n', $otherRelevantInfo );  ?>";
		    document.getElementById("otherRelevantInfo").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'otherRelevantInfo_error'></div></div>
</div>
</div>
</li>

<li>
<div class="additionalInfoLeftCol">
<label>Did you stay in a hostel during your studies?: </label>
<div class=''><input type='radio' name='stayedInHostel' id='stayedInHostel0'   value='Yes'  checked ></input>Yes&nbsp;&nbsp;<input type='radio' name='stayedInHostel' id='stayedInHostel1'   value='No'   ></input>No&nbsp;&nbsp;
<?php if(isset($stayedInHostel) && $stayedInHostel!=""){ ?>
		<script>
		    radioObj = document.forms["OnlineForm"].elements["stayedInHostel"];
		    var radioLength = radioObj.length;
		    for(var i = 0; i < radioLength; i++) {
			    radioObj[i].checked = false;
			    if(radioObj[i].value == "<?php echo $stayedInHostel;?>") {
				    radioObj[i].checked = true;
			    }
		    }
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'stayedInHostel_error'></div></div>
</div>
</div>
</li>

<?php if(is_array($gdpiLocations)): ?>
	<li>
		<label style="font-weight:normal">Preferred GD/PI location: </label>
		<div class=''>
        <select name='preferredGDPILocation' id='preferredGDPILocation' onmouseover="showTipOnline('Select your preferred GD/PI location.',this);" onmouseout='hidetip();'  validate="validateStr"  minlength="1"   maxlength="1500"  required="true" caption="Preferred GD/PI location">
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
		<label style="font-weight:normal">Terms:</label>
		<div class='float_L' style="width:620px; color:#666666; font-style:italic">
		I <student_first_name> <middle_name> <last_name> hereby certify that the information stated in this application by me is true and correct to the best of my knowledge and that nothing has been concealed therein. I have read and understood the conditions mentioned clearly. The degree/diploma certificate will be awarded to me only on the successful completion of the course requirements and examination of the courses applied for. Only enrolling for the course does not make me eligible for the Award.<br />

I understand that in case any information submitted by meils found to be incorrect or not fully furnished by original documents at any point of time, either during the course, or after the completion of the relevant course, it would lead to cancellation of enrolment/dropping my name from the mentioned course without any fee refund. Furthermore, once I am enrolled in the course, I declare that I shall continue to attend and pay the fee as mentioned in the specific fee structure.
		
		<div class="spacer10 clearFix"></div>
       	<div >
        	<input type='checkbox' name='agreeToTermsTapmi[]' id='agreeToTermsTapmi0' value='1' required="true" validate="validateChecked" caption="Please agree to the terms stated above"></input>&nbsp;&nbsp;
			I agree to the terms stated above
			<?php if(isset($agreeToTermsTapmi) && $agreeToTermsTapmi!=""){ ?>
				<script>
		    
			objCheckBoxes = document.getElementsByName("agreeToTermsTapmi[]");
		    var countCheckBoxes = objCheckBoxes.length;
			
		    for(var i = 0; i < countCheckBoxes; i++){
			      objCheckBoxes[i].checked = false;

			      <?php $arr = explode(",",$agreeToTermsTapmi);
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
		<div style='display:none'><div class='errorMsg' id= 'agreeToTermsTapmi0_error'></div></div>
		</div>
	</div>
	</li>

<?php endif;?>
</ul></div></div>
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

	function togglePIO(what)
	{
		if($('pio_details') && $('pioCardNumber') && $('pioIssuingAuthority')){
		if(what == 'yes') {
			$('pio_details').style.display = '';
			$('pioCardNumber').setAttribute('required','true');
			$('pioIssuingAuthority').setAttribute('required','true');
			$('pioDateOfIssue').setAttribute('required','true');
			$('pioDateOfExpiry').setAttribute('required','true');
		}
		else {
			$('pio_details').style.display = 'none';
			$('pioCardNumber').removeAttribute('required');
			$('pioIssuingAuthority').removeAttribute('required');
			$('pioDateOfIssue').removeAttribute('required');
			$('pioDateOfExpiry').removeAttribute('required');
		}
		}
	}

	<?php if(isset($personOfIndianOrigin) && $personOfIndianOrigin=="Yes" && $action != 'updateScore'){ ?>
		togglePIO('yes');
	<?php } else if($action != 'updateScore'){ ?>
		togglePIO('no');
	<?php } ?>
  </script>
