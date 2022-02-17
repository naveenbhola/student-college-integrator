<div class='formChildWrapper'>
	<div class='formSection'>
		<ul>
<li style="width:100%">
<h3 class="upperCase">Entrance Examination Information</h3>
<div class="clearFix"></div>
<div class="additionalInfoLeftCol" style="width:100%">
<label>Qualifying examinations details:&nbsp;</label>
<div class='fieldBoxLarge' style="width:600px">
<input name='courseKIAMS[]' id='courseKIAMS0' type="checkbox" onClick="toggleExamDetails('cat','0');" /> CAT &nbsp;&nbsp;&nbsp;&nbsp;
<input name='courseKIAMS[]' id="courseKIAMS1" type="checkbox" onClick="toggleExamDetails('xat','1');"/> XAT &nbsp;&nbsp;&nbsp;&nbsp;  
<input name='courseKIAMS[]' id="courseKIAMS2" type="checkbox" onClick="toggleExamDetails('cmat','2');"/> CMAT &nbsp;&nbsp;&nbsp;&nbsp;   
<input name='courseKIAMS[]' id="courseKIAMS3" type="checkbox" onClick="toggleExamDetails('gmat','3');"/> GMAT &nbsp;&nbsp;&nbsp;&nbsp;
<input name='courseKIAMS[]' id="courseKIAMS4" type="checkbox" onClick="toggleExamDetails('mat','4');"/> MAT &nbsp;&nbsp;&nbsp;&nbsp;
<div class="clearFix"></div>
<div style='display:none'><div class='errorMsg' id= 'courseKIAMS_error'></div></div>
</div>
</div>
</li>

<li id="catDetails" style="display:none;">
<h3>CAT Score Details</h3>


<div class="spacer15 clearFix"></div>
<div class='additionalInfoLeftCol'>
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

<li id="xatDetails" style="display:none;">
<h3>XAT Score Details</h3>

<div class="spacer15 clearFix"></div>
<div class='additionalInfoLeftCol'>
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

<li id="cmatDetails" style="display:none">
<h3>CMAT Score Details</h3>
<div class="spacer15 clearFix"></div>
<div class='additionalInfoLeftCol'>
<label>Roll Number: </label>
<div class='fieldBoxLarge'>
<input type='text' name='cmatRollNumberAdditional' id='cmatRollNumberAdditional'  validate="validateStr"    caption="Roll Number"   minlength="2"   maxlength="50"     tip="Mention your roll number for the exam. If you have not appeared for this examination enter <b>NA.</b>"   value=''   allowNA='true' />
<?php if(isset($cmatRollNumberAdditional) && $cmatRollNumberAdditional!=""){ ?>
<script>
document.getElementById("cmatRollNumberAdditional").value = "<?php echo str_replace("\n", '\n', $cmatRollNumberAdditional );  ?>";
document.getElementById("cmatRollNumberAdditional").style.color = "";
</script>
<?php } ?>

<div style='display:none'><div class='errorMsg' id= 'cmatRollNumberAdditional_error'></div></div>
</div>
</div>
</li>

<li id="icetDetails" style="display:none">
<h3>ICET Score Details</h3>
<div class='additionalInfoLeftCol'>
<label>Date of Examination: </label>
<div class='fieldBoxLarge'>
<input type='text' name='icetDateOfExaminationAdditionalKIAMS' id='icetDateOfExaminationAdditionalKIAMS' readonly minlength='1' maxlength='10'  validate="validateDateForms"  tip="Choose the date on which the examination was conducted."     onmouseover="showTipOnline('Choose the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('icetDateOfExaminationAdditionalKIAMS'),'icetDateOfExaminationAdditionalKIAMS_dateImg','dd/MM/yyyy');" caption='date'/>
&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='icetDateOfExaminationAdditionalKIAMS_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('icetDateOfExaminationAdditionalKIAMS'),'icetDateOfExaminationAdditionalKIAMS_dateImg','dd/MM/yyyy'); return false;" />
<?php if(isset($icetDateOfExaminationAdditionalKIAMS) && $icetDateOfExaminationAdditionalKIAMS!=""){ ?>
<script>
document.getElementById("icetDateOfExaminationAdditionalKIAMS").value = "<?php echo str_replace("\n", '\n', $icetDateOfExaminationAdditionalKIAMS );  ?>";
document.getElementById("icetDateOfExaminationAdditionalKIAMS").style.color = "";
</script>
<?php } ?>

<div style='display:none'><div class='errorMsg' id= 'icetDateOfExaminationAdditionalKIAMS_error'></div></div>
</div>
</div>
<div class='additionalInfoRightCol'>
<label>Score: </label>
<div class='fieldBoxLarge'>
<input type='text' name='icetScoreAdditionalKIAMS' id='icetScoreAdditionalKIAMS'  validate="validateInteger"    caption="score"   minlength="1"   maxlength="5"     tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter <b>NA.</b>"   value=''  allowNA='true' />
<?php if(isset($icetScoreAdditionalKIAMS) && $icetScoreAdditionalKIAMS!=""){ ?>
<script>
document.getElementById("icetScoreAdditionalKIAMS").value = "<?php echo str_replace("\n", '\n', $icetScoreAdditionalKIAMS );  ?>";
document.getElementById("icetScoreAdditionalKIAMS").style.color = "";
</script>
<?php } ?>

<div style='display:none'><div class='errorMsg' id= 'icetScoreAdditionalKIAMS_error'></div></div>
</div>
</div>
<div class="spacer15 clearFix"></div>
<div class='additionalInfoLeftCol'>
<label>Roll Number: </label>
<div class='fieldBoxLarge'>
<input type='text' name='icetRollNumberAdditionalKIAMS' id='icetRollNumberAdditionalKIAMS'  validate="validateStr"    caption="Roll Number"   minlength="2"   maxlength="50"     tip="Mention your roll number for the exam. If you have not appeared for this examination enter <b>NA.</b>"   value=''   allowNA='true'/>
<?php if(isset($icetRollNumberAdditionalKIAMS) && $icetRollNumberAdditionalKIAMS!=""){ ?>
<script>
document.getElementById("icetRollNumberAdditionalKIAMS").value = "<?php echo str_replace("\n", '\n', $icetRollNumberAdditionalKIAMS );  ?>";
document.getElementById("icetRollNumberAdditionalKIAMS").style.color = "";
</script>
<?php } ?>

<div style='display:none'><div class='errorMsg' id= 'cmatRollNumberAdditional_error'></div></div>
</div>
</div>
</li>

<li id="gmatDetails" style="display:none;">
<h3>GMAT Score Details</h3>
<div class="spacer15 clearFix"></div>
<div class='additionalInfoLeftCol'>
<label>Roll Number: </label>
<div class='fieldBoxLarge'>
<input type='text' name='gmatRollNumberAdditional' id='gmatRollNumberAdditional'   validate="validateStr"    caption="Roll Number"   minlength="2"   maxlength="50"    tip="Mention your roll number for the exam. If you have not appeared for this examination enter <b>NA.</b>"   value=''   allowNA='true' />
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

<li id="matDetails" style="display:none;">
<h3>MAT Score Details</h3>
<div class="spacer15 clearFix"></div>
<div class='additionalInfoLeftCol'>
<label>Roll Number: </label>
<div class='fieldBoxLarge'>
<input type='text' name='matRollNumberAdditional' id='matRollNumberAdditional'   validate="validateStr"    caption="Roll Number"   minlength="2"   maxlength="50"    tip="Mention your roll number for the exam. If you have not appeared for this examination enter <b>NA.</b>"   value=''   allowNA='true' />
<?php if(isset($matRollNumberAdditional) && $matRollNumberAdditional!=""){ ?>
<script>
document.getElementById("matRollNumberAdditional").value = "<?php echo str_replace("\n", '\n', $matRollNumberAdditional );  ?>";
document.getElementById("matRollNumberAdditional").style.color = "";
</script>
<?php } ?>

<div style='display:none'><div class='errorMsg' id= 'matRollNumberAdditional_error'></div></div>
</div>
</div>

<div class='additionalInfoRightCol' >
<label>Form No: </label>
<div class='fieldBoxLarge'>
<input type='text' name='matFormnoAdditionalKIAMS' id='matFormnoAdditionalKIAMS'   validate="validateFloat"    caption="Percentile"   minlength="1"   maxlength="15"         tip="Mention your form no in the exam. If you don't know your form no, enter <b>NA.</b>"   value=''   allowNA='true' />
<?php if(isset($matFormnoAdditionalKIAMS) && $matFormnoAdditionalKIAMS!=""){ ?>
<script>
document.getElementById("matFormnoAdditionalKIAMS").value = "<?php echo str_replace("\n", '\n', $matFormnoAdditionalKIAMS );  ?>";
document.getElementById("matFormnoAdditionalKIAMS").style.color = "";
</script>
<?php } ?>

<div style='display:none'><div class='errorMsg' id= 'matFormnoAdditionalKIAMS_error'></div></div>
</div>
</div>
</li>

			<?php if($action != 'updateScore'):?>
			<li>
				<h3 class="upperCase">Additional Education Qualification Details</h3>
				<div class='additionalInfoLeftCol'>
				<label>Class 10th Subjects: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='class10SubjectsKIAMS' id='class10SubjectsKIAMS'  validate="validateStr"   required="true"   caption="subjects"   minlength="1"   maxlength="200"     tip="Enter the subjects that you studied in class 10th. Subjects should be seperated by a comma."   value='' csv="true"  />
				<?php if(isset($class10SubjectsKIAMS) && $class10SubjectsKIAMS!=""){ ?>
				  <script>
				      document.getElementById("class10SubjectsKIAMS").value = "<?php echo str_replace("\n", '\n', $class10SubjectsKIAMS );  ?>";
				      document.getElementById("class10SubjectsKIAMS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'class10SubjectsKIAMS_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Class 12th Subjects: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='class12SubjectsKIAMS' id='class12SubjectsKIAMS'  validate="validateStr"   required="true"   caption="subjects"   minlength="1"   maxlength="200"     tip="Enter the subjects that you studied in class 12th. Subjects should be seperated by a comma."   value='' csv="true"  />
				<?php if(isset($class12SubjectsKIAMS) && $class12SubjectsKIAMS!=""){ ?>
				  <script>
				      document.getElementById("class12SubjectsKIAMS").value = "<?php echo str_replace("\n", '\n', $class12SubjectsKIAMS);  ?>";
				      document.getElementById("class12SubjectsKIAMS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'class12SubjectsKIAMS_error'></div></div>
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
				<label><?php echo $graduationCourseName;?> Specialization: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gradSubjectsKIAMS' id='gradSubjectsKIAMS'  validate="validateStr"   required="true"   caption="specialization"   minlength="1"   maxlength="200"     tip="Enter the specialization for your graduation here for example mechanical engineering, economics, commerce etc."   value='' csv="true"  />
				<?php if(isset($gradSubjectsKIAMS) && $gradSubjectsKIAMS!=""){ ?>
				  <script>
				      document.getElementById("gradSubjectsKIAMS").value = "<?php echo str_replace("\n", '\n', $gradSubjectsKIAMS );  ?>";
				      document.getElementById("gradSubjectsKIAMS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradSubjectsKIAMS_error'></div></div>
				</div>
				</div>
			

			<?php
			if(count($otherCourses)>0) {
				foreach($otherCourses as $otherCourseId => $otherCourseName) {
					$subjects = 'otherCourseSubject_mul_'.$otherCourseId;
					$subjectsVal = $$subjects;
			?>
<?php if($otherCourseId==2 || $otherCourseId==4){?>
			<li>
<?php } ?>
				<div <?php if($otherCourseId%2==1){?>class='additionalInfoRightCol'<?php }else{?> class='additionalInfoLeftCol'<?php }?>>
				<label><?php echo $otherCourseName;?> Specialization: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='<?php echo $subjects; ?>' id='<?php echo $subjects; ?>'  validate="validateStr"   required="true"   caption="specialization"   minlength="1"   maxlength="200"     tip="Enter the specialization for your graduation here for example mechanical engineering, economics, commerce etc."   value='' csv="true"  />
				<?php if(isset($subjectsVal) && $subjectsVal!=""){ ?>
				  <script>
				      document.getElementById("<?php echo $subjects; ?>").value = "<?php echo str_replace("\n", '\n', $subjectsVal );  ?>";
				      document.getElementById("<?php echo $subjects; ?>").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= '<?php echo $subjects; ?>_error'></div></div>
				</div>
				</div>
         <?php if($otherCourseId==1 || $otherCourseId==3 || $otherCourseId==4){?>
			</li>
<?php } ?>
			<?php
				}
			}
			?>

			<li>
				<h3 class="upperCase">Please list a max of five of your achievements. <span style="color:#666; font-weight:normal; text-transform:none">(Academic, non academic, personal, etc.) Attach Scanned Documents if Applicable</span></h3>
				<div class='additionalInfoLeftCol' style="width:100%">
				<label>Achievements: </label>
				<div class='fieldBoxLarge' style="width:600px;">
				<textarea name='achievements1KIAMS' id='achievements1KIAMS'         tip="If you have any acedemic or extra curricular achievements or you have won an honour or award in the same, please mention it here."  style="width:590px; height:60px" validate="validateStr" caption="achievements" minlenght="1" maxlength="500"  ></textarea>
				<?php if(isset($achievements1KIAMS) && $achievements1KIAMS!=""){ ?>
				  <script>
				      document.getElementById("achievements1KIAMS").value = "<?php echo str_replace("\n", '\n', $achievements1KIAMS );  ?>";
				      document.getElementById("achievements1KIAMS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'achievements1KIAMS_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol' style="width:100%">
				<label>&nbsp;</label>
				<div class='fieldBoxLarge'  style="width:600px;">
				<textarea name='achievements2KIAMS' id='achievements2KIAMS'         tip="If you have any acedemic or extra curricular achievements or you have won an honour or award in the same, please mention it here."   style="width:590px; height:60px" validate="validateStr" caption="achievements" minlenght="1" maxlength="500" ></textarea>
				<?php if(isset($achievements2KIAMS) && $achievements2KIAMS!=""){ ?>
				  <script>
				      document.getElementById("achievements2KIAMS").value = "<?php echo str_replace("\n", '\n', $achievements2KIAMS );  ?>";
				      document.getElementById("achievements2KIAMS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'achievements2KIAMS_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol' style="width:100%">
				<label>&nbsp;</label>
				<div class='fieldBoxLarge'  style="width:600px;">
				<textarea name='achievements3KIAMS' id='achievements3KIAMS'         tip="If you have any acedemic or extra curricular achievements or you have won an honour or award in the same, please mention it here."  style="width:590px; height:60px" validate="validateStr" caption="achievements" minlenght="1" maxlength="500"  ></textarea>
				<?php if(isset($achievements3KIAMS) && $achievements3KIAMS!=""){ ?>
				  <script>
				      document.getElementById("achievements3KIAMS").value = "<?php echo str_replace("\n", '\n', $achievements3KIAMS );  ?>";
				      document.getElementById("achievements3KIAMS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'achievements3KIAMS_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'  style="width:100%;">
				<label>&nbsp;</label>
				<div class='fieldBoxLarge'  style="width:600px;">
				<textarea name='achievements4KIAMS' id='achievements4KIAMS' tip="If you have any acedemic or extra curricular achievements or you have won an honour or award in the same, please mention it here."  style="width:590px; height:60px" validate="validateStr" caption="achievements" minlenght="1" maxlength="500"  ></textarea>
				<?php if(isset($achievements4KIAMS) && $achievements4KIAMS!=""){ ?>
				  <script>
				      document.getElementById("achievements4KIAMS").value = "<?php echo str_replace("\n", '\n', $achievements4KIAMS );  ?>";
				      document.getElementById("achievements4KIAMS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'achievements4KIAMS_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'  style="width:100%;">
				<label>&nbsp;</label>
				<div class='fieldBoxLarge'  style="width:600px;">
				<textarea name='achievements5KIAMS' id='achievements5KIAMS'         tip="If you have any acedemic or extra curricular achievements or you have won an honour or award in the same, please mention it here."  style="width:590px; height:60px" validate="validateStr" caption="achievements" minlenght="1" maxlength="500"  ></textarea>
				<?php if(isset($achievements5KIAMS) && $achievements5KIAMS!=""){ ?>
				  <script>
				      document.getElementById("achievements5KIAMS").value = "<?php echo str_replace("\n", '\n', $achievements5KIAMS );  ?>";
				      document.getElementById("achievements5KIAMS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'achievements5KIAMS_error'></div></div>
				</div>
				</div>
			</li>

<li>
<div class='additionalInfoLeftCol' style="width:100%">
<h3 class="upperCase">Age:</h3>
<label>Age: </label>
<div class='fieldBoxLarge'>
<input type='text' name='ageKIAMS' id='ageKIAMS'  validate="validateInteger"    caption="Age"   minlength="1"   maxlength="2"     tip="Enter yor Age"   value=''   required="true" />
<?php if(isset($ageKIAMS) && $ageKIAMS!=""){ ?>
<script>
document.getElementById("ageKIAMS").value = "<?php echo str_replace("\n", '\n', $ageKIAMS );  ?>";
document.getElementById("ageKIAMS").style.color = "";
</script>
<?php } ?>

<div style='display:none'><div class='errorMsg' id= 'ageKIAMS_error'></div></div>
</div>
</div>
</li>

<li>
<div class='additionalInfoLeftCol' style="width:100%">
<h3 class="upperCase">Work Experience:</h3>
<label>Work Experience: </label>
<div class='fieldBoxLarge' style="width:450px">
<input type='radio' name='expKIAMS' id='expKIAMS0'   value='YES'  <?php if($expKIAMS=='YES'){ ?> checked <?php } ?>   onmouseover="showTipOnline('If you have experience, select YES else select NO',this);" onmouseout="hidetip();" onclick="hideShowExperinceField('YES');"></input><span  onmouseover="showTipOnline('If you have experience, select YES else select NO',this);" onmouseout="hidetip();"/> Yes</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="radio"  name='expKIAMS' id='expKIAMS1'   value='NO'  <?php if($expKIAMS=='NO' || !isset($expKIAMS)){ ?> checked <?php } ?>   onmouseover="showTipOnline('If you have experience, select YES else select NO',this);" onmouseout="hidetip();" onclick="hideShowExperinceField('NO');"></input><span  onmouseover="showTipOnline('If you have experience, select YES else select NO',this);" onmouseout="hidetip();"/> No</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<span style="display:none;" id="expFieldDiv">If Yes, Number of Months : <input type="text" style="width:70px" id="experienceMonthKIAMS" name="experienceMonthKIAMS" validate="validateInteger"    caption="Experience"   minlength="1"   maxlength="2" /></span>
<?php if(isset($experienceMonthKIAMS) && $experienceMonthKIAMS!=""){ ?>

<script>

document.getElementById("experienceMonthKIAMS").value = "<?php echo str_replace("\n", '\n', $experienceMonthKIAMS );  ?>";

document.getElementById("experienceMonthKIAMS").style.color = "";

</script>

<?php } ?>

<div class="clearFix"></div>

<div style='display:none;padding-left:7px;clear:both;float:left'><div class='errorMsg' id= 'experienceMonthKIAMS_error'></div></div>
<?php if(isset($expKIAMS) && $expKIAMS!=""){ ?>

<script>

radioObj = document.forms["OnlineForm"].elements["expKIAMS"];

var radioLength = radioObj.length;

for(var i = 0; i < radioLength; i++) {

radioObj[i].checked = false;

if(radioObj[i].value == "<?php echo $expKIAMS;?>") {

radioObj[i].checked = true;

}

}

</script>

<?php }



?>
</div>
</div>
</li>
			<li>
				<h3 class="upperCase">Preference of Study Campus (Rank in order of preference) :</h3>
				<div class='additionalInfoLeftCol'>
				<label>First preference: </label>
				<div class='fieldBoxLarge'>
				<select blurMethod="checkValidPreference(1);" name='pref1KIAMS' id='pref1KIAMS'  style="width:97px;"  tip="Please select the KIAMS campus where you prefer to study. Select your first preference."   onmouseover="showTipOnline('Please select the KIAMS campus where you prefer to study. Select your first preference.',this);" onmouseout="hidetip();" >
				<option value=''>Select</option>
				<option value='Harihar' selected>Harihar</option><option value='Pune' >Pune</option></select>
				<?php if(isset($pref1KIAMS) && $pref1KIAMS!=""){ ?>
			      <script>
				  var selObj = document.getElementById("pref1KIAMS"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $pref1KIAMS;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'pref1KIAMS_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Second preference: </label>
				<div class='fieldBoxLarge'>
				<select blurMethod="checkValidPreference(2);" name='pref2KIAMS' id='pref2KIAMS'  style="width:97px;"  tip="Please select the KIAMS campus where you prefer to study. Select your second preference."   onmouseover="showTipOnline('Please select the KIAMS campus where you prefer to study. Select your second preference.',this);" onmouseout="hidetip();" >
				<option value=''>Select</option>
				<option value='Harihar' >Harihar</option><option value='Pune' selected>Pune</option>
				</select>
				<?php if(isset($pref2KIAMS) && $pref2KIAMS!=""){ ?>
			      <script>
				  var selObj = document.getElementById("pref2KIAMS"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $pref2KIAMS;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'pref2KIAMS_error'></div></div>
				</div>
				</div>
			</li>

			<?php if(is_array($gdpiLocations)): ?>
			<li>
				<label style="font-weight:normal">Preference of Centre for Group Work / Interview: </label>
				<div class='float_L'>
			<select name='preferredGDPILocation' id='preferredGDPILocation' onmouseover="showTipOnline('Select your preferred GD/PI location.',this);" onmouseout='hidetip();'  validate="validateStr"  minlength="1"   maxlength="1500"  required="true" caption="Preferred GD/PI location">
				<option value=''>Select</option>
				<?php foreach($gdpiLocations as $gdpiLocation): ?>
					<?php //if($gdpiLocation['city_name']=='Harihar' || $gdpiLocation['city_name']=='Pune'){ ?>
						<option value='<?php echo $gdpiLocation['city_id']; ?>'><?php echo $gdpiLocation['city_name']; ?></option>
					<?php //} ?>
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
				I confirm that to the best of my knowledge the information contained in this application is true and accurate. I have gone through the contents of the Prospectus and agree to all the conditions stipulated therein and if admitted, will also abide by the rules and regulations of KIAMS as may be in force from time to time.
				<div class="spacer10 clearFix"></div>
				<div >
				<input type='checkbox' name='agreeToTermsKIAMS' id='agreeToTermsKIAMS' value='1' required="true" validate="validateChecked" caption="Please agree to the terms stated above"></input>&nbsp;I agree to the terms stated above

			      <?php if(isset($agreeToTermsKIAMS) && $agreeToTermsKIAMS!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["agreeToTermsKIAMS"];
				    var countCheckBoxes = 1;
				    for(var i = 0; i < countCheckBoxes; i++){ 
					      objCheckBoxes.checked = false;
					      <?php $arr = explode(",",$agreeToTermsKIAMS);
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
				<div style='display:none'><div class='errorMsg' id= 'agreeToTermsKIAMS_error'></div></div>


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

	function checkValidPreference(id) {
		if(id==1) sId = 2; else sId = 1;
		var selectedPrefObj = document.getElementById('pref'+id+'KIAMS'); 
		var selectedPref = selectedPrefObj.options[selectedPrefObj.selectedIndex].value;
		var selObj = document.getElementById('pref'+sId+'KIAMS'); 
		var selPref = selObj.options[selObj.selectedIndex].value;
		if(selectedPref == selPref && selectedPrefObj!=''){
			$('pref'+id+'KIAMS'+'_error').innerHTML = 'Same preference can’t be set.';
			$('pref'+id+'KIAMS'+'_error').parentNode.style.display = '';
			//Select the blank option
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
			$('pref'+id+'KIAMS'+'_error').innerHTML = '';
			$('pref'+id+'KIAMS'+'_error').parentNode.style.display = 'none';
		}
		return true;
	}
function toggleExamDetails(id,num){

cb = $('courseKIAMS'+num);
if(cb.checked) {
if($(id+'Details')) {
$(id+'Details').style.display = '';
if(id=='cat' || id=='xat' || id=='gmat' || id=='mat'){
$(id+'ScoreAdditional').setAttribute('required','true');
$(id+'RollNumberAdditional').setAttribute('required','true');
$(id+'PercentileAdditional').setAttribute('required','true');
$(id+'DateOfExaminationAdditional').setAttribute('required','true');
}else if(id=='cmat'){
$(id+'ScoreAdditional').setAttribute('required','true');
$(id+'RollNumberAdditional').setAttribute('required','true');
$(id+'RankAdditional').setAttribute('required','true');
$(id+'DateOfExaminationAdditional').setAttribute('required','true');
}else{
$(id+'ScoreAdditionalKIAMS').setAttribute('required','true');
$(id+'RollNumberAdditionalKIAMS').setAttribute('required','true');
$(id+'PercentileAdditionalKIAMS').setAttribute('required','true');
$(id+'DateOfExaminationAdditionalKIAMS').setAttribute('required','true');
$(id+'FormnoAdditionalKIAMS').setAttribute('required','true');

}
}
}
else {
if($(id+'Details')) {
$(id+'Details').style.display = 'none';
}       
if(id=='cat' || id=='xat' || id=='gmat'){     
if($(id+'DateOfExaminationAdditional')) {
$(id+'DateOfExaminationAdditional').value = '';
}
if($(id+'ScoreAdditional')) {
$(id+'ScoreAdditional').value = '';
}
if($(id+'RollNumberAdditional')) {
$(id+'RollNumberAdditional').value = '';
}
if($(id+'PercentileAdditional')) {
$(id+'PercentileAdditional').value = '';
}
$(id+'ScoreAdditional').removeAttribute('required');
$(id+'RollNumberAdditional').removeAttribute('required');
$(id+'PercentileAdditional').removeAttribute('required');
$(id+'DateOfExaminationAdditional').removeAttribute('required');
}else{
if($(id+'DateOfExaminationAdditional')) {
$(id+'DateOfExaminationAdditional').value = '';
}
if($(id+'ScoreAdditional')) {
$(id+'ScoreAdditional').value = '';
}
if($(id+'RollNumberAdditional')) {
$(id+'RollNumberAdditional').value = '';
}
if($(id+'RankAdditional')) {
$(id+'RankAdditional').value = '';
}
$(id+'ScoreAdditional').removeAttribute('required');
$(id+'RollNumberAdditional').removeAttribute('required');
$(id+'RankAdditional').removeAttribute('required');
$(id+'DateOfExaminationAdditional').removeAttribute('required');
}
}
}
</script>
<?php
if( (isset($catDateOfExaminationAdditional) && $catDateOfExaminationAdditional!='') ||  (isset($catScoreAdditional) && $catScoreAdditional!="") || (isset($catPercentileAdditional) && $catPercentileAdditional!="") || (isset($catRollNumberAdditional) && $catRollNumberAdditional!="")){
echo "<script>document.getElementById('courseKIAMS0').checked = true; toggleExamDetails('cat','0');</script>";
}
if( (isset($xatDateOfExaminationAdditional) && $xatDateOfExaminationAdditional!='') ||  (isset($xatScoreAdditional) && $xatScoreAdditional!="") || (isset($xatPercentileAdditional) && $xatPercentileAdditional!="") || (isset($xatRollNumberAdditional) && $xatRollNumberAdditional!="")){
echo "<script>document.getElementById('courseKIAMS1').checked = true; toggleExamDetails('xat','1');</script>";
}
if( (isset($cmatDateOfExaminationAdditional) && $cmatDateOfExaminationAdditional!='') ||  (isset($cmatScoreAdditional) && $cmatScoreAdditional!="") || (isset($cmatRankAdditional) && $cmatRankAdditional!="") || (isset($cmatRollNumberAdditional) && $cmatRollNumberAdditional!="")){
echo "<script>document.getElementById('courseKIAMS2').checked = true; toggleExamDetails('cmat','2');</script>";
}
if( (isset($gmatDateOfExaminationAdditional) && $gmatDateOfExaminationAdditional!='') ||  (isset($gmatScoreAdditional) && $gmatScoreAdditional!="") || (isset($gmatPercentileAdditional) && $gmatPercentileAdditional!="") || (isset($gmatRollNumberAdditional) && $gmatRollNumberAdditional!="")){
echo "<script>document.getElementById('courseKIAMS3').checked = true; toggleExamDetails('gmat','3');</script>";
}
if( (isset($matDateOfExaminationAdditional) && $matDateOfExaminationAdditional!='') ||  (isset($matScoreAdditional) && $matScoreAdditional!="") || (isset($matPercentileAdditional) && $matPercentileAdditional!="") || (isset($matRollNumberAdditional) && $matRollNumberAdditional!="")){
echo "<script>document.getElementById('courseKIAMS4').checked = true; toggleExamDetails('mat','4');</script>";
}
?>
<?php if($expKIAMS=='YES' && $expKIAMS!=''){ echo "<script>if($('expFieldDiv')){ $('expFieldDiv').style.display='';} if($('experienceMonthKIAMS')){ $('experienceMonthKIAMS').setAttribute('required','true');} </script>"; } ?>
<script>

function hideShowExperinceField(val){

    if(val=='YES'){$('expFieldDiv').style.display='';$('experienceMonthKIAMS').setAttribute('required','true');}else{$('expFieldDiv').style.display='None';$('experienceMonthKIAMS').removeAttribute('required');$('experienceMonthKIAMS_error').innerHTML='';$('experienceMonthKIAMS').value='';}

    }

    </script>
