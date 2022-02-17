<script>
function checkTestScore(obj){
var key = obj.value.toLowerCase();
if(obj.value == "MAT" || obj.value == "XAT" ||  obj.value == "CAT" || obj.value == "GMAT"){ 
   var objects1 = new Array(key+"DateOfExaminationAdditional",key+"RollNumberAdditional",key+"ScoreAdditional",key+"PercentileAdditional");
   
}
if(obj.value == "CMAT"){
   var objects1 = new Array(key+"DateOfExaminationAdditional",key+"RollNumberAdditional",key+"ScoreAdditional",key+"PercentileAdditionalITM");
}
if(obj.value == "TANCET" ||  obj.value == "ICET" || obj.value == "KMAT"){
var objects1 = new Array(key+"DateOfExaminationAdditional",key+"RollNumberAdditional",key+"PercentileAdditional");
}
if(obj.value == "MHCET"){
var objects1 = new Array(key+"DateOfExaminationAdditional",key+"RollNumberAdditional",key+"PercentileAdditionalITM");
}
if(obj){
     if( obj.checked == false ){
   $(key+'1').style.display = 'none';
   $(key+'2').style.display = 'none';
if($(key+'3') !== null && $(key+'3') !== 'undefined')
$(key+'3').style.display = 'none';
   //Set the required paramters when any Exam is hidden
   resetExamFields(objects1);
     }
     else{
   $(key+'1').style.display = '';
   $(key+'2').style.display = '';
if($(key+'3') !== null && $(key+'3') !== 'undefined')
$(key+'3').style.display = '';
   //Set the required paramters when any Exam is shown
   setExamFields(objects1);
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
   document.getElementById(objectsArr[i]+'_error').innerHTML = '';
   document.getElementById(objectsArr[i]+'_error').parentNode.style.display = 'none';
}
  }

  function checkValidCampusPreference(id){
if(id==1){ id='preferredGDPILocation'; sId = 'pref2IBA';}
else if(id==2){ id='pref2ITM'; sId = 'preferredGDPILocation';}
var selectedPrefObj = document.getElementById(id); 
var selectedPref = selectedPrefObj.options[selectedPrefObj.selectedIndex].innerHTML;
var selObj1 = document.getElementById(sId); 
var selPref1 = selObj1.options[selObj1.selectedIndex].innerHTML;
if( (selectedPref == selPref1 && selectedPref!='' )){
$(id+'_error').innerHTML = 'Same preference can\'t be set.';
$(id+'_error').parentNode.style.display = '';
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
$(id+'_error').innerHTML = '';
$(id+'_error').parentNode.style.display = 'none';
}
return true;
  }
</script>
<style>
.attachMoreDocBlock label{
float : none;}
</style>
<div class='formChildWrapper'>
<div class='formSection'>
<ul>
<?php if($action != 'updateScore'): ?>

<li>
<h3 class='upperCase'>Personal Details</h3>
<div class='additionalInfoLeftCol'>
<label>Facebook ID: </label>
<div class='fieldBoxLarge'>
<input type='text' name='facebookIdITM' id='facebookIdITM'    validate="validateStr"  caption="Facebook Id"   minlength="1"   maxlength="200"     tip="Enter your Facebook profile ID"   value=''   />
<?php if(isset($facebookIdITM) && $facebookIdITM!=""){ ?>
 <script>
     document.getElementById("facebookIdITM").value = "<?php echo str_replace("\n", '\n', $facebookIdITM );  ?>";
     document.getElementById("facebookIdITM").style.color = "";
 </script>
<?php } ?>
<div style='display:none'><div class='errorMsg' id= 'facebookIdITM_error'></div></div>
</div>
</div>

<div class='additionalInfoRightCol'>
<label>Twitter ID: </label>
<div class='fieldBoxLarge'>
<input type='text' name='twitterIdITM' id='twitterIdITM'     validate="validateStr"  caption="Twitter Id"   minlength="1"   maxlength="200"    tip="Enter your Twitter profile ID"   value=''   />
<?php if(isset($twitterIdITM) && $twitterIdITM!=""){ ?>
 <script>
     document.getElementById("twitterIdITM").value = "<?php echo str_replace("\n", '\n', $twitterIdITM );  ?>";
     document.getElementById("twitterIdITM").style.color = "";
 </script>
<?php } ?>
<div style='display:none'><div class='errorMsg' id= 'twitterIdITM_error'></div></div>
</div>
</div>
</li>

<li>
<h3 class='upperCase'>Personal Information (additional information)</h3>
<div class='additionalInfoLeftCol'>
<label>Parent's/Guardian's Email: </label>
<div class='fieldBoxLarge'>
<input type='text' name='parentEmailITM' id='parentEmailITM'    validate="validateEmail"  caption="Email"   minlength="1"   maxlength="200"     tip="Enter your Parent's/Guardian's Email ID"   value=''   />
<?php if(isset($parentEmailITM) && $parentEmailITM!=""){ ?>
 <script>
     document.getElementById("parentEmailITM").value = "<?php echo str_replace("\n", '\n', $parentEmailITM );  ?>";
     document.getElementById("parentEmailITM").style.color = "";
 </script>
<?php } ?>
<div style='display:none'><div class='errorMsg' id= 'parentEmailITM_error'></div></div>
</div>
</div>
</li>

<!-- <li>
<h3 class='upperCase'>Specialization and Campus Preference</h3>
<div class='additionalInfoLeftCol' style="width: 800px;">
<label>Choice of specialization: </label>
<div class='fieldBoxLarge' style="width: 480px;">
<input type='radio'   required="true"   name='specializationITM' id='specializationITM0'   value='Marketing'  checked   onmouseover="showTipOnline('Enter your choice of specialization',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Enter your choice of specialization',this);" onmouseout="hidetip();" >Marketing</span>&nbsp;&nbsp;
<input type='radio'   required="true"   name='specializationITM' id='specializationITM1'   value='Retail Management & Marketing'     onmouseover="showTipOnline('Enter your choice of specialization',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Enter your choice of specialization',this);" onmouseout="hidetip();" >Retail Management & Marketing</span>&nbsp;&nbsp;
<input type='radio'   required="true"   name='specializationITM' id='specializationITM2'   value='Finance'     onmouseover="showTipOnline('Enter your choice of specialization',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Enter your choice of specialization',this);" onmouseout="hidetip();" >Finance</span>&nbsp;&nbsp;
<?php if(isset($specializationITM) && $specializationITM!=""){ ?>
 <script>
     radioObj = document.forms["OnlineForm"].elements["specializationITM"];
     var radioLength = radioObj.length;
     for(var i = 0; i < radioLength; i++) {
     radioObj[i].checked = false;
     if(radioObj[i].value == "<?php echo $specializationITM;?>") {
     radioObj[i].checked = true;
     }
     }
 </script>
<?php } ?>
<div style='display:none'><div class='errorMsg' id= 'specializationITM_error'></div></div>
</div>
</div>
</li>
-->
<li>
<h3 class='upperCase'>Specialization and Campus Preference</h3>
<div class='additionalInfoLeftCol' style="width: 800px;">
<label>Choice of specialization: </label>
<select name='specializations_ITM' onchange="choicespecial(this);" id='specializations_ITM' onmouseover="showTipOnline('Select your choice of specialization.',this);" onmouseout='hidetip();'  validate="validateSelect" required="true" caption="Specialization field">
<option value="" >Select</option>
<optgroup label="ITM-Business School">
<option value="Finance">Finanace</option>
<option value="Marketing">Marketing</option>
<option value="Human Resource Management">Human Resource Management</option>
<option value="Supply Chain and Operations Management">Supply Chain and Operations Management</option>
<option value="Information Technology">Information Technology</option>
<option value="Marketing & Digital Media">Marketing & Digital Media</option>
<option value="Pharma Management">Pharma Management</option>
<option value="AgriBusiness Management">AgriBusiness Management</option>
</optgroup>
<optgroup label="ITM- Global Leadership Centre">
<option value="Retail Management & Marketing">Retail Management & Marketing</option>
<option value="Human Resources Management">Human Resources Management</option>
<option value="International Business">International Business</option>
</optgroup>
<optgroup label="ITM- Institute of Financial">
<option value="Financial Markets">Financial Markets</option>
</optgroup>
</select>
<?php if(isset($specializations_ITM) && $specializations_ITM!=""){ ?>
<script>
var selObj = document.getElementById("specializations_ITM"); 
var A= selObj.options, L= A.length;
while(L){
if (A[--L].value== "<?php echo $specializations_ITM;?>"){
selObj.selectedIndex= L;
L= 0;
}
}
</script>
 <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'specializations_ITM_error'></div></div>
</div>
</li>


<!-- <li>
<div class='additionalInfoLeftCol' style="width: 850px;">
<label>Choice of campus: </label>
<div class='fieldBoxLarge' style="width: 500px;">
<input type='radio'   required="true"   name='campusITM' id='campusITM0'   value='Navi Mumbai(Khargar)'  checked   onmouseover="showTipOnline('Enter your preferred campus',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Enter your preferred campus',this);" onmouseout="hidetip();" >Navi Mumbai(Khargar)</span>&nbsp;&nbsp;
<input type='radio'   required="true"   name='campusITM' id='campusITM1'   value='Delhi(Noida) (upcoming)*'     onmouseover="showTipOnline('Enter your preferred campus',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Enter your preferred campus',this);" onmouseout="hidetip();" >Delhi(Noida) (upcoming)*</span>&nbsp;&nbsp;
<input type='radio'   required="true"   name='campusITM' id='campusITM2'   value='Bengaluru'     onmouseover="showTipOnline('Enter your preferred campus',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Enter your preferred campus',this);" onmouseout="hidetip();" >Bengaluru</span>&nbsp;&nbsp;
<?php if(isset($campusITM) && $campusITM!=""){ ?>
 <script>
     radioObj = document.forms["OnlineForm"].elements["campusITM"];
     var radioLength = radioObj.length;
     for(var i = 0; i < radioLength; i++) {
     radioObj[i].checked = false;
     if(radioObj[i].value == "<?php echo $campusITM;?>") {
     radioObj[i].checked = true;
     }
     }
 </script>
<?php } ?>
<div style='display:none'><div class='errorMsg' id= 'campusITM_error'></div></div>
</div>
</div>
</li>
-->
<li>
<div class='additionalInfoLeftCol' style="width: 850px;">
<label>Choice of campus: </label>
<select name='campus1_ITM' id='campus1_ITM' onmouseover="showTipOnline('Select your preferred campus location.',this);" onmouseout='hidetip();'  validate="validateStr"  minlength="1"   maxlength="1500" caption="Preferred campus location">
<option value="">Select</option>
<option value="Bengaluru">Bengaluru</option>
       <option value="Chennai">Chennai</option>
<option value="Warangal">Warangal</option>
<option value="Noida (upcoming)">Noida (upcoming)</option>
<option value="Mumabi (Dombivali)">Mumbai (Dombivali)</option>
<option value="Navi Mumabi(Khargar)">Navi Mumbai(Khargar)</option>
</select>
<?php if(isset($campus1_ITM) && $campus1_ITM!=""){ ?>
<script>
var selObj = document.getElementById("campus1_ITM"); 
var A= selObj.options, L= A.length;
while(L){
if (A[--L].value== "<?php echo $campus1_ITM;?>"){
selObj.selectedIndex= L;
L= 0;
}
}
</script>
                                <?php } ?>
                            <div style='display:none'><div class='errorMsg' id= 'campus1_ITM_error'></div></div>
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


<?php
$i=0;
if(count($otherCourses)>0) { 
foreach($otherCourses as $otherCourseId => $otherCourseName) {
$cityTown = 'otherCourseCity_mul_'.$otherCourseId;
$cityTownVal = $$cityTown;
$i++;

?>

<li>
                
<div class='additionalInfoRightCol'>
<label><?php echo $otherCourseName;?> City/Town: </label>
<div class='fieldBoxLarge'>
<input type='text' name='<?php echo $cityTown; ?>' id='<?php echo $cityTown; ?>'  validate="validateStr"    caption="city/town"   minlength="1"   maxlength="200"     tip="Enter City/Town for this course."   value=''  />
<?php if(isset($cityTownVal) && $cityTownVal!=""){ ?>
 <script>
     document.getElementById("<?php echo $cityTown; ?>").value = "<?php echo str_replace("\n", '\n', $cityTownVal );  ?>";
     document.getElementById("<?php echo $cityTown; ?>").style.color = "";
 </script>
<?php } ?>
<div style='display:none'><div class='errorMsg' id= '<?php echo $cityTown; ?>_error'></div></div>
</div>
</div>
                <div class="clearFix spacer15"></div>
</li>

<?php
}
}
?>

<li>
<h3 class='upperCase'>Education Information (additional information)</h3>
<div class='additionalInfoLeftCol'>
<label><?php echo $graduationCourseName;?> City/Town: </label>
<div class='fieldBoxLarge'>
<input type='text' name='graduationCityITM' id='graduationCityITM'    validate="validateStr"  caption="City"   minlength="1"   maxlength="200"     tip="Enter <?php echo $graduationCourseName;?> City/Town"   value=''   />
<?php if(isset($graduationCityITM) && $graduationCityITM!=""){ ?>
 <script>
     document.getElementById("graduationCityITM").value = "<?php echo str_replace("\n", '\n', $graduationCityITM );  ?>";
     document.getElementById("graduationCityITM").style.color = "";
 </script>
<?php } ?>
<div style='display:none'><div class='errorMsg' id= 'graduationCityITM_error'></div></div>
</div>
</div>
</li>

<li>
<div class='additionalInfoLeftCol'>
<label>Higher Secondary School  City/Town: </label>
<div class='fieldBoxLarge'>
<input type='text' name='class12CityITM' id='class12CityITM'    validate="validateStr"  caption="City"   minlength="1"   maxlength="200"     tip="Enter your Higher Secondary School City/Town"   value=''   />
<?php if(isset($class12CityITM) && $class12CityITM!=""){ ?>
 <script>
     document.getElementById("class12CityITM").value = "<?php echo str_replace("\n", '\n', $class12CityITM );  ?>";
     document.getElementById("class12CityITM").style.color = "";
 </script>
<?php } ?>
<div style='display:none'><div class='errorMsg' id= 'class12CityITM_error'></div></div>
</div>
</div>

<div class='additionalInfoRightCol'>
<label>School  City/Town: </label>
<div class='fieldBoxLarge'>
<input type='text' name='class10CityITM' id='class10CityITM'   validate="validateStr"  caption="City"   minlength="1"   maxlength="200"      tip="Enter your School City/Town"   value=''   />
<?php if(isset($class10CityITM) && $class10CityITM!=""){ ?>
 <script>
     document.getElementById("class10CityITM").value = "<?php echo str_replace("\n", '\n', $class10CityITM );  ?>";
     document.getElementById("class10CityITM").style.color = "";
 </script>
<?php } ?>
<div style='display:none'><div class='errorMsg' id= 'class10CityITM_error'></div></div>
</div>
</div>
</li>

<?php endif; ?>
<li>
<h3 class='upperCase'>Exams</h3><div class='additionalInfoLeftCol'  style="width:1015px;">
<label>Aptitude Test Appeared: </label>
<div class='fieldBoxLarge'  style="width:641px;">
<input onClick="checkTestScore(this);" type='checkbox' validate="validateCheckedGroup" required="true" caption="the tests" name='testNamesITM[]' id='testNamesITM0'   value='CAT'    onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" >CAT</span>&nbsp;&nbsp;
<input onClick="checkTestScore(this);" type='checkbox' validate="validateCheckedGroup" required="true" caption="the tests" name='testNamesITM[]' id='testNamesITM1'   value='XAT'    onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" >XAT</span>&nbsp;&nbsp;
<input onClick="checkTestScore(this);" type='checkbox' validate="validateCheckedGroup" required="true" caption="the tests" name='testNamesITM[]' id='testNamesITM2'   value='MAT'    onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" >MAT</span>&nbsp;&nbsp;
<input onClick="checkTestScore(this);" type='checkbox' validate="validateCheckedGroup" required="true" caption="the tests" name='testNamesITM[]' id='testNamesITM3'   value='CMAT'    onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" >CMAT</span>&nbsp;&nbsp;
<input onClick="checkTestScore(this);" type='checkbox' validate="validateCheckedGroup" required="true" caption="the tests" name='testNamesITM[]' id='testNamesITM4'   value='GMAT'    onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" >GMAT</span>&nbsp;&nbsp;
<input onClick="checkTestScore(this);" type='checkbox' validate="validateCheckedGroup" required="true" caption="the tests" name='testNamesITM[]' id='testNamesITM5'   value='TANCET'    onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" >TANCET</span>&nbsp;&nbsp;
<input onClick="checkTestScore(this);" type='checkbox' validate="validateCheckedGroup" required="true" caption="the tests" name='testNamesITM[]' id='testNamesITM6'   value='MHCET'    onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" >MHCET</span>&nbsp;&nbsp;
<input onClick="checkTestScore(this);" type='checkbox' validate="validateCheckedGroup" required="true" caption="the tests" name='testNamesITM[]' id='testNamesITM7'   value='ICET'    onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" >ICET</span>&nbsp;&nbsp;
<input onClick="checkTestScore(this);" type='checkbox' validate="validateCheckedGroup" required="true" caption="the tests" name='testNamesITM[]' id='testNamesITM8'   value='KMAT'    onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" >KMAT</span>&nbsp;&nbsp;

<?php if(isset($testNamesITM) && $testNamesITM!=""){ ?>
<script>
   objCheckBoxes = document.forms["OnlineForm"].elements["testNamesITM[]"];
   var countCheckBoxes = objCheckBoxes.length;
   for(var i = 0; i < countCheckBoxes; i++){
     objCheckBoxes[i].checked = false;

     <?php $arr = explode(",",$testNamesITM);
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
<div style='display:none'><div class='errorMsg' id= 'testNamesITM_error'></div></div>
</div>
</div>
</li>
<li id="cat1" style="display:none;">
<div class='additionalInfoLeftCol'>
<label>CAT REGN NO: </label>
<div class='fieldBoxLarge'>
<input class="textboxLarge" type='text' name='catRollNumberAdditional' id='catRollNumberAdditional'  validate="validateStr"    caption="regn no"   minlength="2"   maxlength="50"        tip="Mention your Registration number for the exam.If you do not have the roll number, enter NA."  allowNA='true' value=''   />
<?php if(isset($catRollNumberAdditional) && $catRollNumberAdditional!=""){ ?>
 <script>
     document.getElementById("catRollNumberAdditional").value = "<?php echo str_replace("\n", '\n', $catRollNumberAdditional );  ?>";
     document.getElementById("catRollNumberAdditional").style.color = "";
 </script>
<?php } ?>
<div style='display:none'><div class='errorMsg' id= 'catRollNumberAdditional_error'></div></div>
</div>
</div>

<div class='additionalInfoRightCol'>
<label>CAT Date: </label>
<div class='fieldBoxLarge'>
<input type='text' name='catDateOfExaminationAdditional' id='catDateOfExaminationAdditional' readonly maxlength='10'    validate="validateDateForms"  caption="date"      tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('catDateOfExaminationAdditional'),'catDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />
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
</li>

<li id="cat2" style="display:none; border-bottom:1px;">
<div class='additionalInfoLeftCol'>
<label>CAT Score: </label>
<div class='fieldBoxLarge'>
<input type='text' name='catScoreAdditional' id='catScoreAdditional'   validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"     tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter NA."  allowNA="true"   value=''  />
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
<label>CAT Percentile: </label>
<div class='fieldBoxLarge'>
<input type='text' name='catPercentileAdditional' id='catPercentileAdditional'   validate="validateFloat" allowNA="true"  caption="Percentile"   minlength="1"   maxlength="5"      tip="Mention your percentile in the exam. If you don't know your percentile, enter NA."   value=''  />
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

<li id="cat3" style="display:none; border-bottom:1px dotted #c0c0c0; padding-bottom:15px;">
<div class='additionalInfoLeftCol'>
<label>CAT Results Awaited: </label>
<div class='fieldBoxLarge'>
<input type='radio'    name='catResultITM' id='catResultITM0'   value='Yes'  checked   onmouseover="showTipOnline('Select if you have not received CAT results',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select if you have not received CAT results',this);" onmouseout="hidetip();" >Yes</span>&nbsp;&nbsp;
<input type='radio'    name='catResultITM' id='catResultITM1'   value='No'     onmouseover="showTipOnline('Select if you have not received CAT results',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select if you have not received CAT results',this);" onmouseout="hidetip();" >No</span>&nbsp;&nbsp;
<?php if(isset($catResultITM) && $catResultITM!=""){ ?>
 <script>
     radioObj = document.forms["OnlineForm"].elements["catResultITM"];
     var radioLength = radioObj.length;
     for(var i = 0; i < radioLength; i++) {
     radioObj[i].checked = false;
     if(radioObj[i].value == "<?php echo $catResultITM;?>") {
     radioObj[i].checked = true;
     }
     }
 </script>
<?php } ?>
<div style='display:none'><div class='errorMsg' id= 'catResultITM_error'></div></div>
</div>
</div>
</li>
<?php
   if(isset($testNamesITM) && $testNamesITM!="" && strpos($testNamesITM,'CAT')!==false){ ?>
   <script>
   checkTestScore(document.getElementById('testNamesITM0'));
   </script>
<?php
   }
?>
<li id="xat1" style="display:none;">
<div class='additionalInfoLeftCol'>
<label>XAT REGN NO: </label>
<div class='fieldBoxLarge'>
<input class="textboxLarge" type='text' name='xatRollNumberAdditional' id='xatRollNumberAdditional'   tip="Mention your Registration number for the exam.If you do not have the roll number, enter NA."  allowNA='true'  validate="validateStr"    caption="regn no"   minlength="2"   maxlength="50"     value=''   />
<?php if(isset($xatRollNumberAdditional) && $xatRollNumberAdditional!=""){ ?>
 <script>
     document.getElementById("xatRollNumberAdditional").value = "<?php echo str_replace("\n", '\n', $xatRollNumberAdditional );  ?>";
     document.getElementById("xatRollNumberAdditional").style.color = "";
 </script>
<?php } ?>
<div style='display:none'><div class='errorMsg' id= 'xatRollNumberAdditional_error'></div></div>
</div>
</div>

<div class='additionalInfoRightCol'>
<label>XAT Date: </label>
<div class='fieldBoxLarge'>
<input type='text' name='xatDateOfExaminationAdditional' id='xatDateOfExaminationAdditional' readonly maxlength='10'     validate="validateDateForms"  caption="date"     tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('xatDateOfExaminationAdditional'),'xatDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />
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
</li>

<li id="xat2" style="display:none; border-bottom:1px ;">
<div class='additionalInfoLeftCol'>
<label>XAT Score: </label>
<div class='fieldBoxLarge'>
<input type='text' name='xatScoreAdditional' id='xatScoreAdditional'  validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"         tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter NA."   value=''  allowNA="true" />
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
<label>XAT Percentile: </label>
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
<li id="xat3" style="display:none; border-bottom:1px dotted #c0c0c0; padding-bottom:15px;">
<div class='additionalInfoLeftCol'>
<label>XAT Results Awaited: </label>
<div class='fieldBoxLarge'>
<input type='radio'    name='xatResultITM' id='xatResultITM0'   value='Yes'  checked   onmouseover="showTipOnline('Select if you have not received XAT results',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select if you have not received XAT results',this);" onmouseout="hidetip();" >Yes</span>&nbsp;&nbsp;
<input type='radio'    name='xatResultITM' id='xatResultITM1'   value='No'     onmouseover="showTipOnline('Select if you have not received XAT results',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select if you have not received XAT results',this);" onmouseout="hidetip();" >No</span>&nbsp;&nbsp;
<?php if(isset($xatResultITM) && $xatResultITM!=""){ ?>
 <script>
     radioObj = document.forms["OnlineForm"].elements["xatResultITM"];
     var radioLength = radioObj.length;
     for(var i = 0; i < radioLength; i++) {
     radioObj[i].checked = false;
     if(radioObj[i].value == "<?php echo $xatResultITM;?>") {
     radioObj[i].checked = true;
     }
     }
 </script>
<?php } ?>
<div style='display:none'><div class='errorMsg' id= 'xatResultITM_error'></div></div>
</div>
</div>
</li>
<?php
   if(isset($testNamesITM) && $testNamesITM!="" && strpos($testNamesITM,'XAT')!==false){ ?>
   <script>
   checkTestScore(document.getElementById('testNamesITM1'));
   </script>
<?php
   }
?>
<li id="mat1" style="display:none;">
<div class='additionalInfoLeftCol'>
<label>MAT REGN NO: </label>
<div class='fieldBoxLarge'>
<input class="textboxLarge" type='text' name='matRollNumberAdditional' id='matRollNumberAdditional'     validate="validateStr"    caption="regn no"   minlength="2"   maxlength="50"   tip="Mention your Registration number for the exam.If you do not have the roll number, enter NA."  allowNA='true'    value=''   />
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
<label>MAT Date: </label>
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

<li id="mat2" style="display:none; ">
<div class='additionalInfoLeftCol'>
<label>MAT Score: </label>
<div class='fieldBoxLarge'>
<input type='text' name='matScoreAdditional' id='matScoreAdditional'  validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"         tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter NA."   value=''  allowNA="true" />
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
<label>MAT Percentile: </label>
<div class='float_L'>
  <input class="textboxSmaller"  type='text' name='matPercentileAdditional' id='matPercentileAdditional'  validate="validateFloat" allowNA="true"  caption="Percentile"   minlength="1"   maxlength="5"     tip="Mention your percentile in the exam. If you don't know your percentile, enter NA."   value=''  />
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
<li id="mat3" style="display:none; border-bottom:1px dotted #c0c0c0; padding-bottom:15px;">
<div class='additionalInfoLeftCol'>
<label>MAT Results Awaited: </label>
<div class='fieldBoxLarge'>
<input type='radio'    name='matResultITM' id='matResultITM0'   value='Yes'  checked   onmouseover="showTipOnline('Select if you have not received MAT results',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select if you have not received MAT results',this);" onmouseout="hidetip();" >Yes</span>&nbsp;&nbsp;
<input type='radio'    name='matResultITM' id='matResultITM1'   value='No'     onmouseover="showTipOnline('Select if you have not received MAT results',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select if you have not received MAT results',this);" onmouseout="hidetip();" >No</span>&nbsp;&nbsp;
<?php if(isset($matResultITM) && $matResultITM!=""){ ?>
 <script>
     radioObj = document.forms["OnlineForm"].elements["matResultITM"];
     var radioLength = radioObj.length;
     for(var i = 0; i < radioLength; i++) {
     radioObj[i].checked = false;
     if(radioObj[i].value == "<?php echo $matResultITM;?>") {
     radioObj[i].checked = true;
     }
     }
 </script>
<?php } ?>
<div style='display:none'><div class='errorMsg' id= 'matResultITM_error'></div></div>
</div>
</div>
</li>

<?php
   if(isset($testNamesITM) && $testNamesITM!="" && strpos($testNamesITM,'MAT')!==false){ ?>
   <script>
   checkTestScore(document.getElementById('testNamesITM2'));
   </script>
<?php
   }
?>
<li id="cmat1" style="display:none;">
<div class="additionalInfoLeftCol">
                                <label>CMAT REGN NO: </label>
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

<div class='additionalInfoRightCol'>
<label>CMAT Date: </label>
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
<li id="cmat2" style="display:none;">
<div class='additionalInfoLeftCol'>
<label>CMAT Score: </label>
<div class='fieldBoxLarge'>
<input type='text' name='cmatScoreAdditional' id='cmatScoreAdditional'  validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"     tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter NA."   value=''    allowNA = 'true' />
<?php if(isset($cmatScoreAdditional) && $cmatScoreAdditional!=""){ ?>
 <script>
     document.getElementById("cmatScoreAdditional").value = "<?php echo str_replace("\n", '\n', $cmatScoreAdditional );  ?>";
     document.getElementById("cmatScoreAdditional").style.color = "";
 </script>
<?php } ?>
<div style='display:none'><div class='errorMsg' id= 'cmatScoreAdditional_error'></div></div>
</div>
</div>
<div class='additionalInfoRightCol'>
<label>CMAT Percentile: </label>
<div class='fieldBoxLarge'>
<input type='text' name='cmatPercentileAdditionalITM' id='cmatPercentileAdditionalITM'  validate="validateFloat"    caption="percentile"   minlength="1"   maxlength="5"     tip="Mention your percentile in the exam. If you don't know your percentile, you can leave this field blank, enter NA."   value=''    allowNA = 'true' />
<?php if(isset($cmatPercentileAdditionalITM) && $cmatPercentileAdditionalITM!=""){ ?>
 <script>
     document.getElementById("cmatPercentileAdditionalITM").value = "<?php echo str_replace("\n", '\n', $cmatPercentileAdditionalITM);  ?>";
     document.getElementById("cmatPercentileAdditionalITM").style.color = "";
 </script>
<?php } ?>
<div style='display:none'><div class='errorMsg' id= 'cmatPercentileAdditionalITM_error'></div></div>
</div>
</div>
</li>
<li id="cmat3" style="display:none; border-bottom:1px dotted #c0c0c0; padding-bottom:15px;">
<div class='additionalInfoLeftCol'>
<label>CMAT Results Awaited: </label>
<div class='fieldBoxLarge'>
<input type='radio'    name='cmatResultITM' id='cmatResultITM0'   value='Yes'  checked   onmouseover="showTipOnline('Select if you have not received CMAT results',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select if you have not received CMAT results',this);" onmouseout="hidetip();" >Yes</span>&nbsp;&nbsp;
<input type='radio'    name='cmatResultITM' id='cmatResultITM1'   value='No'     onmouseover="showTipOnline('Select if you have not received CMAT results',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select if you have not received CMAT results',this);" onmouseout="hidetip();" >No</span>&nbsp;&nbsp;
<?php if(isset($cmatResultITM) && $cmatResultITM!=""){ ?>
 <script>
     radioObj = document.forms["OnlineForm"].elements["cmatResultITM"];
     var radioLength = radioObj.length;
     for(var i = 0; i < radioLength; i++) {
     radioObj[i].checked = false;
     if(radioObj[i].value == "<?php echo $cmatResultITM;?>") {
     radioObj[i].checked = true;
     }
     }
 </script>
<?php } ?>
<div style='display:none'><div class='errorMsg' id= 'cmatResultITM_error'></div></div>
</div>
</div>
</li>

<?php
   if(isset($testNamesITM) && $testNamesITM!="" && strpos($testNamesITM,'CMAT')!==false){ ?>
   <script>
   checkTestScore(document.getElementById('testNamesITM3'));
   </script>
<?php
   }
?>
<li id="gmat1" style="display:none;">
<div class='additionalInfoLeftCol'>
<label>GMAT REGN NO: </label>
<div class='fieldBoxLarge'>
<input class="textboxLarge" type='text' name='gmatRollNumberAdditional' id='gmatRollNumberAdditional'     validate="validateStr"    caption="regn no"   minlength="2"   maxlength="50"     tip="Mention your Registration number for the exam.If you do not have the roll number, enter NA."  allowNA='true' value=''   />
<?php if(isset($gmatRollNumberAdditional) && $gmatRollNumberAdditional!=""){ ?>
 <script>
     document.getElementById("gmatRollNumberAdditional").value = "<?php echo str_replace("\n", '\n', $gmatRollNumberAdditional );  ?>";
     document.getElementById("gmatRollNumberAdditional").style.color = "";
 </script>
<?php } ?>
<div style='display:none'><div class='errorMsg' id= 'gmatRollNumberAdditional_error'></div></div>
</div>
</div>

<div class='additionalInfoRightCol'>
<label>GMAT Date: </label>
<div class='fieldBoxLarge'>
<input type='text' name='gmatDateOfExaminationAdditional' id='gmatDateOfExaminationAdditional' readonly maxlength='10'    validate="validateDateForms"  caption="date"      tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('gmatDateOfExaminationAdditional'),'gmatDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />
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
</li>
<li id="gmat2" style="display:none; ">
<div class='additionalInfoLeftCol'>
<label>GMAT Score: </label>
<div class='fieldBoxLarge'>
<input type='text' name='gmatScoreAdditional' id='gmatScoreAdditional'  validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"        tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter NA."   value=''  allowNA="true" />
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
<label>GMAT Percentile: </label>
<div class='fieldBoxLarge'>
<input type='text' name='gmatPercentileAdditional' id='gmatPercentileAdditional'  validate="validateFloat" caption="percentile"   minlength="1"   maxlength="5"     tip="Mention your percentile in the exam. If you don't know your percentile, you can leave this field blank, enter NA."   value=''    allowNA = 'true' />
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
<li id="gmat3" style="display:none; border-bottom:1px dotted #c0c0c0; padding-bottom:15px;">
<div class='additionalInfoLeftCol'>
<label>GMAT Results Awaited: </label>
<div class='fieldBoxLarge'>
<input type='radio'    name='gmatResultITM' id='gmatResultITM0'   value='Yes'  checked   onmouseover="showTipOnline('Select if you have not received GMAT results',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select if you have not received GMAT results',this);" onmouseout="hidetip();" >Yes</span>&nbsp;&nbsp;
<input type='radio'    name='gmatResultITM' id='gmatResultITM1'   value='No'     onmouseover="showTipOnline('Select if you have not received GMAT results',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select if you have not received GMAT results',this);" onmouseout="hidetip();" >No</span>&nbsp;&nbsp;
<?php if(isset($gmatResultITM) && $gmatResultITM!=""){ ?>
 <script>
     radioObj = document.forms["OnlineForm"].elements["gmatResultITM"];
     var radioLength = radioObj.length;
     for(var i = 0; i < radioLength; i++) {
     radioObj[i].checked = false;
     if(radioObj[i].value == "<?php echo $gmatResultITM;?>") {
     radioObj[i].checked = true;
     }
     }
 </script>
<?php } ?>
<div style='display:none'><div class='errorMsg' id= 'gmatResultITM_error'></div></div>
</div>
</div>
</li>
<?php
   if(isset($testNamesITM) && $testNamesITM!="" && strpos($testNamesITM,'GMAT')!==false){ ?>
   <script>
   checkTestScore(document.getElementById('testNamesITM4'));
   </script>
<?php
   }
?>
<li id="tancet1" style="display:none;">
<div class='additionalInfoLeftCol'>
<label>TANCET REGN NO: </label>
<div class='fieldBoxLarge'>
<input class="textboxLarge" type='text' name='tancetRollNumberAdditional' id='tancetRollNumberAdditional'  validate="validateStr"   caption="regn no"   minlength="2"   maxlength="50"        tip="Mention your Registration number for the exam.If you do not have the roll number, enter NA."  allowNA='true' value=''   />
<?php if(isset($tancetRollNumberAdditional) && $tancetRollNumberAdditional!=""){ ?>
 <script>
     document.getElementById("tancetRollNumberAdditional").value = "<?php echo str_replace("\n", '\n', $tancetRollNumberAdditional );  ?>";
     document.getElementById("tancetRollNumberAdditional").style.color = "";
 </script>
<?php } ?>
<div style='display:none'><div class='errorMsg' id= 'tancetRollNumberAdditional_error'></div></div>
</div>
</div>

<div class='additionalInfoRightCol'>
<label>TANCET Date: </label>
<div class='fieldBoxLarge'>
<input type='text' name='tancetDateOfExaminationAdditional' id='tancetDateOfExaminationAdditional' readonly maxlength='10'    validate="validateDateForms"  caption="date"    tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('tancetDateOfExaminationAdditional'),'tancetDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />
&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='tancetDateOfExaminationAdditional_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('tancetDateOfExaminationAdditional'),'tancetDateOfExaminationAdditional_dateImg','dd/MM/yyyy'); return false;" />
<?php if(isset($tancetDateOfExaminationAdditional) && $tancetDateOfExaminationAdditional!=""){ ?>
 <script>
     document.getElementById("tancetDateOfExaminationAdditional").value = "<?php echo str_replace("\n", '\n', $tancetDateOfExaminationAdditional );  ?>";
     document.getElementById("tancetDateOfExaminationAdditional").style.color = "";
 </script>
<?php } ?>
<div style='display:none'><div class='errorMsg' id= 'tancetDateOfExaminationAdditional_error'></div></div>
</div>
</div>
</li>

<li id="tancet2" style="display:none; border-bottom:1px dotted #c0c0c0; padding-bottom:15px; ">
<div class='additionalInfoLeftCol'>
<label>TANCET Percentile: </label>
<div class='fieldBoxLarge'>
<input type='text' name='tancetPercentileAdditional' id='tancetPercentileAdditional' validate="validateFloat" allowNA="true"  caption="Percentile"   minlength="1"   maxlength="5"      tip="Mention your percentile in the exam. If you don't know your percentile, enter NA."   value=''  />
<?php if(isset($tancetPercentileAdditional) && $tancetPercentileAdditional!=""){ ?>
 <script>
     document.getElementById("tancetPercentileAdditional").value = "<?php echo str_replace("\n", '\n', $tancetPercentileAdditional );  ?>";
     document.getElementById("tancetPercentileAdditional").style.color = "";
 </script>
<?php } ?>
<div style='display:none'><div class='errorMsg' id= 'tancetPercentileAdditional_error'></div></div>
</div>
</div>
<div class='additionalInfoRightCol'>
<label>TANCET Results Awaited: </label>
<div class='fieldBoxLarge'>
<input type='radio'    name='tancetResultITM' id='tancetResultITM0'   value='Yes'  checked   onmouseover="showTipOnline('Select if you have not received TANCET results',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select if you have not received TANCET results',this);" onmouseout="hidetip();" >Yes</span>&nbsp;&nbsp;
<input type='radio'    name='tancetResultITM' id='tancetResultITM1'   value='No'     onmouseover="showTipOnline('Select if you have not received TANCET results',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select if you have not received TANCET results',this);" onmouseout="hidetip();" >No</span>&nbsp;&nbsp;
<?php if(isset($tancetResultITM) && $tancetResultITM!=""){ ?>
 <script>
     radioObj = document.forms["OnlineForm"].elements["tancetResultITM"];
     var radioLength = radioObj.length;
     for(var i = 0; i < radioLength; i++) {
     radioObj[i].checked = false;
     if(radioObj[i].value == "<?php echo $tancetResultITM;?>") {
     radioObj[i].checked = true;
     }
     }
 </script>
<?php } ?>
<div style='display:none'><div class='errorMsg' id= 'tancetResultITM_error'></div></div>
</div>
</div>
</li>


<?php
   if(isset($testNamesITM) && $testNamesITM!="" && strpos($testNamesITM,'CAT')!==false){ ?>
   <script>
   checkTestScore(document.getElementById('testNamesITM5'));
   </script>
<?php
   }
?>
<li id="mhcet1" style="display:none;">
<div class='additionalInfoLeftCol'>
<label>MHCET REGN NO: </label>
<div class='fieldBoxLarge'>
<input class="textboxLarge" type='text' name='mhcetRollNumberAdditional' id='mhcetRollNumberAdditional'  validate="validateStr"  caption="regn no"   minlength="2"   maxlength="50"        tip="Mention your Registration number for the exam.If you do not have the roll number, enter NA."  allowNA='true' value=''   />
<?php if(isset($mhcetRollNumberAdditional) && $mhcetRollNumberAdditional!=""){ ?>
 <script>
     document.getElementById("mhcetRollNumberAdditional").value = "<?php echo str_replace("\n", '\n', $mhcetRollNumberAdditional );  ?>";
     document.getElementById("mhcetRollNumberAdditional").style.color = "";
 </script>
<?php } ?>
<div style='display:none'><div class='errorMsg' id= 'mhcetRollNumberAdditional_error'></div></div>
</div>
</div>

<div class='additionalInfoRightCol'>
<label>MHCET Date: </label>
<div class='fieldBoxLarge'>
<input type='text' name='mhcetDateOfExaminationAdditional' id='mhcetDateOfExaminationAdditional' readonly maxlength='10'    validate="validateDateForms"    caption="date"      tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('mhcetDateOfExaminationAdditional'),'mhcetDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />
&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='mhcetDateOfExaminationAdditional_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('mhcetDateOfExaminationAdditional'),'mhcetDateOfExaminationAdditional_dateImg','dd/MM/yyyy'); return false;" />
<?php if(isset($mhcetDateOfExaminationAdditional) && $mhcetDateOfExaminationAdditional!=""){ ?>
 <script>
     document.getElementById("mhcetDateOfExaminationAdditional").value = "<?php echo str_replace("\n", '\n', $mhcetDateOfExaminationAdditional );  ?>";
     document.getElementById("mhcetDateOfExaminationAdditional").style.color = "";
 </script>
<?php } ?>
<div style='display:none'><div class='errorMsg' id= 'mhcetDateOfExaminationAdditional_error'></div></div>
</div>
</div>
</li>

<li id="mhcet2" style="display:none; border-bottom:1px dotted #c0c0c0; padding-bottom:15px;">
<div class='additionalInfoLeftCol'>
<label>MHCET Percentile: </label>
<div class='fieldBoxLarge'>
<input type='text' name='mhcetPercentileAdditionalITM' id='mhcetPercentileAdditionalITM'   validate="validateFloat"  allowNA="true"  caption="Percentile"   minlength="1"   maxlength="5"      tip="Mention your percentile in the exam. If you don't know your percentile, enter NA."   value=''  />
<?php if(isset($mhcetPercentileAdditionalITM) && $mhcetPercentileAdditionalITM!=""){ ?>
 <script>
     document.getElementById("mhcetPercentileAdditionalITM").value = "<?php echo str_replace("\n", '\n', $mhcetPercentileAdditionalITM );  ?>";
     document.getElementById("mhcetPercentileAdditionalITM").style.color = "";
 </script>
<?php } ?>
<div style='display:none'><div class='errorMsg' id= 'mhcetPercentileAdditionalITM_error'></div></div>
</div>
</div>
<div class='additionalInfoRightCol'>
<label>MHCET Results Awaited: </label>
<div class='fieldBoxLarge'>
<input type='radio'    name='mhcetResultITM' id='mhcetResultITM0'   value='Yes'  checked   onmouseover="showTipOnline('Select if you have not received mhcet results',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select if you have not received mhcet results',this);" onmouseout="hidetip();" >Yes</span>&nbsp;&nbsp;
<input type='radio'    name='mhcetResultITM' id='mhcetResultITM1'   value='No'     onmouseover="showTipOnline('Select if you have not received mhcet results',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select if you have not received mhcet results',this);" onmouseout="hidetip();" >No</span>&nbsp;&nbsp;
<?php if(isset($mhcetResultITM) && $mhcetResultITM!=""){ ?>
 <script>
     radioObj = document.forms["OnlineForm"].elements["mhcetResultITM"];
     var radioLength = radioObj.length;
     for(var i = 0; i < radioLength; i++) {
     radioObj[i].checked = false;
     if(radioObj[i].value == "<?php echo $mhcetResultITM;?>") {
     radioObj[i].checked = true;
     }
     }
 </script>
<?php } ?>
<div style='display:none'><div class='errorMsg' id= 'mhcetResultITM_error'></div></div>
</div>
</div>
</li>


<?php
   if(isset($testNamesITM) && $testNamesITM!="" && strpos($testNamesITM,'MHCET')!==false){ ?>
   <script>
   checkTestScore(document.getElementById('testNamesITM6'));
   </script>
<?php
   }
?>
<li id="icet1" style="display:none;">
<div class='additionalInfoLeftCol'>
<label>ICET REGN NO: </label>
<div class='fieldBoxLarge'>
<input class="textboxLarge" type='text' name='icetRollNumberAdditional' id='icetRollNumberAdditional'  validate="validateStr" rcaption="regn no"   minlength="2"   maxlength="50"        tip="Mention your Registration number for the exam.If you do not have the roll number, enter NA."  allowNA='true' value=''   />
<?php if(isset($icetRollNumberAdditional) && $icetRollNumberAdditional!=""){ ?>
 <script>
     document.getElementById("icetRollNumberAdditional").value = "<?php echo str_replace("\n", '\n', $icetRollNumberAdditional );  ?>";
     document.getElementById("icetRollNumberAdditional").style.color = "";
 </script>
<?php } ?>
<div style='display:none'><div class='errorMsg' id= 'icetRollNumberAdditional_error'></div></div>
</div>
</div>

<div class='additionalInfoRightCol'>
<label>ICET Date: </label>
<div class='fieldBoxLarge'>
<input type='text' name='icetDateOfExaminationAdditional' id='icetDateOfExaminationAdditional' readonly maxlength='10'    validate="validateDateForms"  caption="date"      tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('icetDateOfExaminationAdditional'),'icetDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />
&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='icetDateOfExaminationAdditional_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('icetDateOfExaminationAdditional'),'icetDateOfExaminationAdditional_dateImg','dd/MM/yyyy'); return false;" />
<?php if(isset($icetDateOfExaminationAdditional) && $icetDateOfExaminationAdditional!=""){ ?>
 <script>
     document.getElementById("icetDateOfExaminationAdditional").value = "<?php echo str_replace("\n", '\n', $icetDateOfExaminationAdditional );  ?>";
     document.getElementById("icetDateOfExaminationAdditional").style.color = "";
 </script>
<?php } ?>
<div style='display:none'><div class='errorMsg' id= 'icetDateOfExaminationAdditional_error'></div></div>
</div>
</div>
</li>

<li id="icet2" style="display:none; border-bottom:1px dotted #c0c0c0; padding-bottom:15px; ">
<div class='additionalInfoLeftCol'>
<label>ICET Percentile: </label>
<div class='fieldBoxLarge'>
<input type='text' name='icetPercentileAdditional' id='icetPercentileAdditional'   validate="validateFloat"  allowNA="true"  caption="Percentile"   minlength="1"   maxlength="5"      tip="Mention your percentile in the exam. If you don't know your percentile, enter NA."   value=''  />
<?php if(isset($icetPercentileAdditional) && $icetPercentileAdditional!=""){ ?>
 <script>
     document.getElementById("icetPercentileAdditional").value = "<?php echo str_replace("\n", '\n', $icetPercentileAdditional );  ?>";
     document.getElementById("icetPercentileAdditional").style.color = "";
 </script>
<?php } ?>
<div style='display:none'><div class='errorMsg' id= 'icetPercentileAdditional_error'></div></div>
</div>
</div>
<div class='additionalInfoRightCol'>
<label>ICET Results Awaited: </label>
<div class='fieldBoxLarge'>
<input type='radio'    name='icetResultITM' id='icetResultITM0'   value='Yes'  checked   onmouseover="showTipOnline('Select if you have not received icet results',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select if you have not received icet results',this);" onmouseout="hidetip();" >Yes</span>&nbsp;&nbsp;
<input type='radio'    name='icetResultITM' id='icetResultITM1'   value='No'     onmouseover="showTipOnline('Select if you have not received icet results',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select if you have not received icet results',this);" onmouseout="hidetip();" >No</span>&nbsp;&nbsp;
<?php if(isset($icetResultITM) && $icetResultITM!=""){ ?>
 <script>
     radioObj = document.forms["OnlineForm"].elements["icetResultITM"];
     var radioLength = radioObj.length;
     for(var i = 0; i < radioLength; i++) {
     radioObj[i].checked = false;
     if(radioObj[i].value == "<?php echo $icetResultITM;?>") {
     radioObj[i].checked = true;
     }
     }
 </script>
<?php } ?>
<div style='display:none'><div class='errorMsg' id= 'icetResultITM_error'></div></div>
</div>
</div>
</li>


<?php
   if(isset($testNamesITM) && $testNamesITM!="" && strpos($testNamesITM,'ICET')!==false){ ?>
   <script>
   checkTestScore(document.getElementById('testNamesITM7'));
   </script>
<?php
   }
?>
<li id="kmat1" style="display:none;">
<div class='additionalInfoLeftCol'>
<label>KMAT REGN NO: </label>
<div class='fieldBoxLarge'>
<input class="textboxLarge" type='text' name='kmatRollNumberAdditional' id='kmatRollNumberAdditional'  validate="validateStr"   caption="regn no"   minlength="2"   maxlength="50"        tip="Mention your Registration number for the exam.If you do not have the roll number, enter NA."  allowNA='true' value=''   />
<?php if(isset($kmatRollNumberAdditional) && $kmatRollNumberAdditional!=""){ ?>
 <script>
     document.getElementById("kmatRollNumberAdditional").value = "<?php echo str_replace("\n", '\n', $kmatRollNumberAdditional );  ?>";
     document.getElementById("kmatRollNumberAdditional").style.color = "";
 </script>
<?php } ?>
<div style='display:none'><div class='errorMsg' id= 'kmatRollNumberAdditional_error'></div></div>
</div>
</div>

<div class='additionalInfoRightCol'>
<label>KMAT Date: </label>
<div class='fieldBoxLarge'>
<input type='text' name='kmatDateOfExaminationAdditional' id='kmatDateOfExaminationAdditional' readonly maxlength='10'    validate="validateDateForms" caption="date"      tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('kmatDateOfExaminationAdditional'),'kmatDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />
&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='kmatDateOfExaminationAdditional_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('kmatDateOfExaminationAdditional'),'kmatDateOfExaminationAdditional_dateImg','dd/MM/yyyy'); return false;" />
<?php if(isset($kmatDateOfExaminationAdditional) && $kmatDateOfExaminationAdditional!=""){ ?>
 <script>
     document.getElementById("kmatDateOfExaminationAdditional").value = "<?php echo str_replace("\n", '\n', $kmatDateOfExaminationAdditional );  ?>";
     document.getElementById("kmatDateOfExaminationAdditional").style.color = "";
 </script>
<?php } ?>
<div style='display:none'><div class='errorMsg' id= 'kmatDateOfExaminationAdditional_error'></div></div>
</div>
</div>
</li>

<li id="kmat2" style="display:none; border-bottom:1px dotted #c0c0c0; padding-bottom:15px;">
<div class='additionalInfoLeftCol'>
<label>KMAT Percentile: </label>
<div class='fieldBoxLarge'>
<input type='text' name='kmatPercentileAdditional' id='kmatPercentileAdditional'   validate="validateFloat"  allowNA="true"  caption="Percentile"   minlength="1"   maxlength="5"      tip="Mention your percentile in the exam. If you don't know your percentile, enter NA."   value=''  />
<?php if(isset($kmatPercentileAdditional) && $kmatPercentileAdditional!=""){ ?>
 <script>
     document.getElementById("kmatPercentileAdditional").value = "<?php echo str_replace("\n", '\n', $kmatPercentileAdditional );  ?>";
     document.getElementById("kmatPercentileAdditional").style.color = "";
 </script>
<?php } ?>
<div style='display:none'><div class='errorMsg' id= 'kmatPercentileAdditional_error'></div></div>
</div>
</div>
<div class='additionalInfoRightCol'>
<label>KMAT Results Awaited: </label>
<div class='fieldBoxLarge'>
<input type='radio'    name='kmatResultITM' id='kmatResultITM0'   value='Yes'  checked   onmouseover="showTipOnline('Select if you have not received kmat results',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select if you have not received kmat results',this);" onmouseout="hidetip();" >Yes</span>&nbsp;&nbsp;
<input type='radio'    name='kmatResultITM' id='kmatResultITM1'   value='No'     onmouseover="showTipOnline('Select if you have not received kmat results',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select if you have not received kmat results',this);" onmouseout="hidetip();" >No</span>&nbsp;&nbsp;
<?php if(isset($kmatResultITM) && $kmatResultITM!=""){ ?>
 <script>
     radioObj = document.forms["OnlineForm"].elements["kmatResultITM"];
     var radioLength = radioObj.length;
     for(var i = 0; i < radioLength; i++) {
     radioObj[i].checked = false;
     if(radioObj[i].value == "<?php echo $kmatResultITM;?>") {
     radioObj[i].checked = true;
     }
     }
 </script>
<?php } ?>
<div style='display:none'><div class='errorMsg' id= 'kmatResultITM_error'></div></div>
</div>
</div>
</li>


<?php
   if(isset($testNamesITM) && $testNamesITM!="" && strpos($testNamesITM,'KMAT')!==false){ ?>
   <script>
   checkTestScore(document.getElementById('testNamesITM8'));
   </script>
<?php
   }
?>
<?php if($action != 'updateScore'): ?>
<li>
<h3 class='upperCase'>Work Experience (additional information)</h3>
<div class='additionalInfoLeftCol'>
<label>Total work experience (Months): </label>
<div class='fieldBoxLarge'>
<input type='text' name='totalWorkExITM' id='totalWorkExITM'         tip="Enter your total work experience in months."   value=''   />
<?php if(isset($totalWorkExITM) && $totalWorkExITM!=""){ ?>
 <script>
     document.getElementById("totalWorkExITM").value = "<?php echo str_replace("\n", '\n', $totalWorkExITM );  ?>";
     document.getElementById("totalWorkExITM").style.color = "";
 </script>
<?php } ?>
<div style='display:none'><div class='errorMsg' id= 'totalWorkExITM_error'></div></div>
</div>
</div>
</li>

<li>
<div class='additionalInfoLeftCol'>
<label>Referer information: </label>
<div class='fieldBoxLarge'>
<textarea name='referrerITM' id='referrerITM'         tip="Enter your referer information"    ></textarea>
<?php if(isset($referrerITM) && $referrerITM!=""){ ?>
 <script>
     document.getElementById("referrerITM").value = "<?php echo str_replace("\n", '\n', $referrerITM );  ?>";
     document.getElementById("referrerITM").style.color = "";
 </script>
<?php } ?>
<div style='display:none'><div class='errorMsg' id= 'referrerITM_error'></div></div>
</div>
</div>
</li>

<li>
<h3 class='upperCase'>Bank Loan (additional information)</h3>
<div class='additionalInfoLeftCol'>
<label>Will you be interested to avail bank loan: </label>
<div class='fieldBoxLarge'>
<input type='radio'   required="true"   name='bankLoanITM' id='bankLoanITM0'   value='Yes'  checked   onmouseover="showTipOnline('Select Yes if you are interested to avail bank loan. Else No',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select Yes if you are interested to avail bank loan. Else No',this);" onmouseout="hidetip();" >Yes</span>&nbsp;&nbsp;
<input type='radio'   required="true"   name='bankLoanITM' id='bankLoanITM1'   value='No'     onmouseover="showTipOnline('Select Yes if you are interested to avail bank loan. Else No',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select Yes if you are interested to avail bank loan. Else No',this);" onmouseout="hidetip();" >No</span>&nbsp;&nbsp;
<?php if(isset($bankLoanITM) && $bankLoanITM!=""){ ?>
 <script>
     radioObj = document.forms["OnlineForm"].elements["bankLoanITM"];
     var radioLength = radioObj.length;
     for(var i = 0; i < radioLength; i++) {
     radioObj[i].checked = false;
     if(radioObj[i].value == "<?php echo $bankLoanITM;?>") {
     radioObj[i].checked = true;
     }
     }
 </script>
<?php } ?>
<div style='display:none'><div class='errorMsg' id= 'bankLoanITM_error'></div></div>
</div>
</div>
</li>

<li>
<div class='additionalInfoLeftCol'>
<label>Shall we share your contact details with these Banks and Financial Institutions: </label>
<div class='fieldBoxLarge'>
<input type='radio'   required="true"   name='shareDetailsITM' id='shareDetailsITM0'   value='Yes'  checked   onmouseover="showTipOnline('Select Yes if you are willing to share your contact details. Else No',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select Yes if you are willing to share your contact details. Else No',this);" onmouseout="hidetip();" >Yes</span>&nbsp;&nbsp;
<input type='radio'   required="true"   name='shareDetailsITM' id='shareDetailsITM1'   value='No'     onmouseover="showTipOnline('Select Yes if you are willing to share your contact details. Else No',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select Yes if you are willing to share your contact details. Else No',this);" onmouseout="hidetip();" >No</span>&nbsp;&nbsp;
<?php if(isset($shareDetailsITM) && $shareDetailsITM!=""){ ?>
 <script>
     radioObj = document.forms["OnlineForm"].elements["shareDetailsITM"];
     var radioLength = radioObj.length;
     for(var i = 0; i < radioLength; i++) {
     radioObj[i].checked = false;
     if(radioObj[i].value == "<?php echo $shareDetailsITM;?>") {
     radioObj[i].checked = true;
     }
     }
 </script>
<?php } ?>
<div style='display:none'><div class='errorMsg' id= 'shareDetailsITM_error'></div></div>
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
<label style="font-weight:normal; padding-top:0">Terms:</label>
<!--<label style="font-weight:normal; padding-top:0">Terms:</label>-->
<div class='float_L' style="width:620px; color:#666666; font-style:italic">
I certify that all information provided on this application is complete & accurate.I agree to familarize myself with all the rules and regulations of the programme set forth by the institute & abide by them. I would uphold the standards and respect the principles of the organization for higher learning
</div>
<div class="spacer10 clearFix"></div>
</li>
<li>
<div>
<input type='checkbox'   required="true"    name='agreeToTermsITM' id='agreeToTermsITM'   value=''  checked  validate='validateChecked' caption='Please agree to the terms stated above'></input><span ></span>&nbsp;&nbsp;
<label style="font-weight:normal;">I agree to the terms stated above: </label></div>
<?php if(isset($agreeToTermsITM) && $agreeToTermsITM!=""){ ?>
<script>
   objCheckBoxes = document.forms["OnlineForm"].elements["agreeToTermsITM[]"];
   var countCheckBoxes = objCheckBoxes.length;
   for(var i = 0; i < countCheckBoxes; i++){
     objCheckBoxes[i].checked = false;

     <?php $arr = explode(",",$agreeToTermsITM);
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
<div style="display:none;"><div class='errorMsg' id= 'agreeToTermsITM_error'></div></div>
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
  

  </script>​