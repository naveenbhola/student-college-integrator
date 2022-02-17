<script>

 var gdPiDate_custom = {
  
  "Delhi": ['March 1','March 8','March 15','March 22','March 29','May 3','May 10','May 17','May 24','May 31']
}

var gdPiDate = {
  
  
  "74": ['March 1','March 8','March 15','March 22','March 29','May 3','May 10','May 17','May 24','May 31']
}



  function checkTestScore(obj){
	var key = obj.value.toLowerCase();
	var objects1 = new Array(key+"DateOfExaminationAdditional",key+"RollNumberAdditional",key+"ScoreAdditional",key+"RankAdditional",key+"PercentileAdditional");
	if(obj){
	      if( obj.checked == false ){
		    $(key+'1').style.display = 'none';
		    $(key+'2').style.display = 'none';
		    //Set the required paramters when any Exam is hidden
		    resetExamFields(objects1);
	      }
	      else{
		    $(key+'1').style.display = '';
		    $(key+'2').style.display = '';
		    //Set the required paramters when any Exam is shown
		    setExamFields(objects1);
	      }
	}
  }

function selectGDCustom(select_id,key){
key=key.value;
document.getElementById(select_id).innerHTML='';
select='';
if(key==''){
         $(select_id+'_d').style.display='none';
        return true;
}
else
  $(select_id+'_d').style.display='inline';

 select ='<option value="">Select Date</option>';
 for(var i=0;i<gdPiDate_custom[key].length;i++)
 {
     select +='<option value="'+gdPiDate_custom[key][i]+'">'+gdPiDate_custom[key][i]+'</option>';
 }
document.getElementById(select_id).innerHTML=select;
}


  function selectGD(select_id,key){
   key=key.value;
   document.getElementById(select_id).innerHTML='';
   select='';
   if(key==''){
         $(select_id+'_d').style.display='none';
        return true;
  }
   else
     $(select_id+'_d').style.display='inline';

    select ='<option value="">Select Date</option>';

   for(var i=0;i<gdPiDate[key].length;i++)
   {
        select +='<option value="'+gdPiDate[key][i]+'">'+gdPiDate[key][i]+'</option>';
   }
   document.getElementById(select_id).innerHTML=select;
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
</script>

<div class="formChildWrapper">
	<div class="formSection">
    	<ul>
         <?php if($action != 'updateScore'):?>
			<li>
            	<h3 class="upperCase">Additional Family Details:</h3>
                <div class="additionalInfoLeftCol">
                <label>Father's profession/Designation: </label>
                <div class='fieldBoxLarge'>
                    <input type='text'  name='fatherOccupationSkyline' id='fatherOccupationSkyline'  validate="validateStr" required="true" caption="Father's occupation" minlength="2" maxlength="50" tip="Type in the occupation of your father here or nature of his work, such as Engineer or Business Owner."  value='' class="textboxLarge" />
                    <?php if(isset($fatherOccupationSkyline) && $fatherOccupationSkyline!=""){ ?>
                    <script>
                        document.getElementById("fatherOccupationSkyline").value = "<?php echo str_replace("\n", '\n', $fatherOccupationSkyline );  ?>";
                        document.getElementById("fatherOccupationSkyline").style.color = "";
                    </script>
                    <?php } ?>
                    <div style='display:none'><div class='errorMsg' id= 'fatherOccupationSkyline_error'></div></div>
                </div>
            </div>
            
            <div class="additionalInfoRightCol">
            	<label>Name of organization: </label>
                <div class='fieldBoxLarge'>
                    <input type='text' name='fatherOrganization' id='fatherOrganization' validate="validateStr" required="true" caption="Father's organization" minlength="2"   maxlength="50" tip="Please enter the name of your father's organization/business. <?php echo $NAText; ?>"   value='' class="textboxLarge" />
                    <?php if(isset($fatherOrganization) && $fatherOrganization!=""){ ?>
                        <script>
                            document.getElementById("fatherOrganization").value = "<?php echo str_replace("\n", '\n', $fatherOrganization );  ?>";
                            document.getElementById("fatherOrganization").style.color = "";
                        </script>
                    <?php } ?>
                    <div style='display:none'><div class='errorMsg' id= 'fatherOrganization_error'></div></div>
                </div>
            
            </div>
		</li>
		<li>
        	<div class="additionalInfoLeftCol">
                <label>Mobile No.: </label>
                <div class='fieldBoxLarge'>
                    <input type='text' name='fatherMobileNumberSkyline' id='fatherMobileNumberSkyline'  validate="validateMobileInteger" required="true" allowNA="true" caption="Father's Mobile Number" minlength="10" maxlength="10" tip="Please enter your father's mobile number. <?php echo $NAText; ?>" value='' class="textboxLarge" />
                    <?php if(isset($fatherMobileNumberSkyline) && $fatherMobileNumberSkyline!=""){ ?>
                        <script>
                            document.getElementById("fatherMobileNumberSkyline").value = "<?php echo str_replace("\n", '\n', $fatherMobileNumberSkyline );  ?>";
                            document.getElementById("fatherMobileNumberSkyline").style.color = "";
                        </script>
                    <?php } ?>
                    <div style='display:none'><div class='errorMsg' id= 'fatherMobileNumberSkyline_error'></div></div>
                </div>
            </div>
            
            <div class="additionalInfoRightCol">
            	<label>Email Id: </label>
                <div class='fieldBoxLarge'>
                    <input type='text' name='fatherEmailId' id='fatherEmailId'  validate="validateEmail" required="true" allowNA="true" caption="Email Id" minlength="2" maxlength="200" tip="Please enter your father's email address. <?php echo $NAText; ?>"   value='' class="textboxLarge" />
                    <?php if(isset($fatherEmailId) && $fatherEmailId!=""){ ?>
                    <script>
                        document.getElementById("fatherEmailId").value = "<?php echo str_replace("\n", '\n', $fatherEmailId );  ?>";
                        document.getElementById("fatherEmailId").style.color = "";
                    </script>
                    <?php } ?>
                    <div style='display:none'><div class='errorMsg' id= 'fatherEmailId_error'></div></div>
                </div>
            </div>
		</li>

		<li>
        	 <div class="additionalInfoLeftCol">
                <label>Mother's profession/Designation: </label>
                <div class='fieldBoxLarge'>
                    <input type='text' name='MotherOccupationSkyline' id='MotherOccupationSkyline'  validate="validateStr" required="true" caption="Mother's occupation" minlength="2" maxlength="50" tip="Type in the occupation of your mother here or nature of her work, such as Teacher or Homemaker."  value=''  class="textboxLarge"/>
                    <?php if(isset($MotherOccupationSkyline) && $MotherOccupationSkyline!=""){ ?>
                    <script>
                        document.getElementById("MotherOccupationSkyline").value = "<?php echo str_replace("\n", '\n', $MotherOccupationSkyline );  ?>";
                        document.getElementById("MotherOccupationSkyline").style.color = "";
                    </script>
                    <?php } ?>
                    <div style='display:none'><div class='errorMsg' id= 'MotherOccupationSkyline_error'></div></div>
                </div>
            </div>
            
             <div class="additionalInfoRightCol">
             	<label>Name of organization: </label>
                <div class='fieldBoxLarge'>
                    <input type='text' name='MotherOrganization' id='MotherOrganization'  validate="validateStr"  required="true" caption="Mother's organization" minlength="2"   maxlength="50"  tip="Please enter the name of your Mother's organization/business. <?php echo $NAText; ?>"   value='' class="textboxLarge" />
                    <?php if(isset($MotherOrganization) && $MotherOrganization!=""){ ?>
                    <script>
                        document.getElementById("MotherOrganization").value = "<?php echo str_replace("\n", '\n', $MotherOrganization );  ?>";
                        document.getElementById("MotherOrganization").style.color = "";
                    </script>
                    <?php } ?>
                	<div style='display:none'><div class='errorMsg' id= 'MotherOrganization_error'></div></div>
            	</div>
             </div>
        </li>

		<li>
        	<div class="additionalInfoLeftCol">
            <label>Mobile No.: </label>
            <div class='fieldBoxLarge'>
                <input type='text' name='MotherMobileNumberSkyline' id='MotherMobileNumberSkyline'  validate="validateMobileInteger" required="true" allowNA="true" caption="Mother's Mobile Number"  minlength="10" maxlength="10" tip="Please enter your Mother's mobile number. <?php echo $NAText; ?>"   value='' class="textboxLarge" />
                <?php if(isset($MotherMobileNumberSkyline) && $MotherMobileNumberSkyline!=""){ ?>
                <script>
                    document.getElementById("MotherMobileNumberSkyline").value = "<?php echo str_replace("\n", '\n', $MotherMobileNumberSkyline );  ?>";
                    document.getElementById("MotherMobileNumberSkyline").style.color = "";
                </script>
                <?php } ?>
                <div style='display:none'><div class='errorMsg' id= 'MotherMobileNumberSkyline_error'></div></div>
            </div>
        </div>
        
        <div class="additionalInfoRightCol">
        	<label>Email Id: </label>
            <div class='fieldBoxLarge'>
                <input type='text' name='MotherEmailId' id='MotherEmailId'  validate="validateEmail" required="true" allowNA="true" caption="Email Id" minlength="2" maxlength="200" tip="Please enter your Mother's email address. <?php echo $NAText; ?>"   value='' class="textboxLarge" />
                <?php if(isset($MotherEmailId) && $MotherEmailId!=""){ ?>
                <script>
                    document.getElementById("MotherEmailId").value = "<?php echo str_replace("\n", '\n', $MotherEmailId );  ?>";
                    document.getElementById("MotherEmailId").style.color = "";
                </script>
                <?php } ?>
                <div style='display:none'><div class='errorMsg' id= 'MotherEmailId_error'></div></div>
            </div>
        </div>
	</li>

		<li>
        	<h3 class="upperCase">Guardian Details (For outstation students only):</h3>
    		<div class="additionalInfoLeftCol">
            <label>If outstation student, the Delhi guardian name and address: </label>
            <div class='fieldBoxLarge'>
                <textarea name='GudardianNameAndAddress' id='GudardianNameAndAddress'  validate="validateStr"   required="true"   caption="Gudardian's name and address"   minlength="2"   maxlength="200" tip="If you are an outstation candidate, please enter the name and address of your local guardian in Delhi. <?php echo $NAText; ?>" class="textboxLarge" ></textarea>
    <?php if(isset($GudardianNameAndAddress) && $GudardianNameAndAddress!=""){ ?>
                <script>
                    document.getElementById("GudardianNameAndAddress").value = "<?php echo str_replace("\n", '\n', $GudardianNameAndAddress );  ?>";
                    document.getElementById("GudardianNameAndAddress").style.color = "";
                </script>
                  <?php } ?>
                <div style='display:none'><div class='errorMsg' id= 'GudardianNameAndAddress_error'></div></div>
            </div>
        </div>
        
        <div class="additionalInfoRightCol">
        	<label>Guardian's telephone number: </label>
            <div class='fieldBoxLarge'>
                <input type='text' name='GudardianTelephone' id='GudardianTelephone'  validate="validateStr" required="true" caption="Guardian's telephone" minlength="2"   maxlength="15" tip="Enter landline number of local guardian in Delhi. <?php echo $NAText; ?>"   value='' class="textboxLarge" />
                <?php if(isset($GudardianTelephone) && $GudardianTelephone!=""){ ?>
                <script>
                    document.getElementById("GudardianTelephone").value = "<?php echo str_replace("\n", '\n', $GudardianTelephone );  ?>";
                    document.getElementById("GudardianTelephone").style.color = "";
                </script>
                  <?php } ?>
                <div style='display:none'><div class='errorMsg' id= 'GudardianTelephone_error'></div></div>
            </div>
        </div>
	</li>

	<li>
    	<div class="additionalInfoLeftCol">
            <label>Guardian's mobile number: </label>
            <div class='fieldBoxLarge'>
                <input type='text' name='GudardianMobile' id='GudardianMobile'  validate="validateMobileInteger" required="true" allowNA="true" caption="Guardian's mobile number" minlength="10"   maxlength="10" tip="Enter mobile number of local guardian in Delhi. <?php echo $NAText; ?>"   value='' class="textboxLarge" />
                <?php if(isset($GudardianMobile) && $GudardianMobile!=""){ ?>
                <script>
                    document.getElementById("GudardianMobile").value = "<?php echo str_replace("\n", '\n', $GudardianMobile );  ?>";
                    document.getElementById("GudardianMobile").style.color = "";
                </script>
                <?php } ?>
                <div style='display:none'><div class='errorMsg' id= 'GudardianMobile_error'></div></div>
            </div>
         </div>
         <div class="additionalInfoRightCol">
         	<label>Guardian's email address: </label>
            <div class='fieldBoxLarge'>
                <input type='text' name='GudardianEmail' id='GudardianEmail'  validate="validateEmail" required="true" allowNA="true" caption="Guardian's Email Id" minlength="2"   maxlength="200"  tip="Enter the email address of your local guardian in Delhi. <?php echo $NAText; ?>"   value='' class="textboxLarge" />
                <?php if(isset($GudardianEmail) && $GudardianEmail!=""){ ?>
                <script>
                    document.getElementById("GudardianEmail").value = "<?php echo str_replace("\n", '\n', $GudardianEmail );  ?>";
                    document.getElementById("GudardianEmail").style.color = "";
                </script>
                  <?php } ?>
                <div style='display:none'><div class='errorMsg' id= 'GudardianEmail_error'></div></div>
            </div>
         </div>
	</li>

	<li>
    	<h3 class="upperCase">Additional Educational Details:</h3>
        <div class="clearFix"></div>
    	<label style="font-weight:normal">Class 10th duration <strong style="font-weight:normal; margin-left:10px">from</strong>: </label>
        <div class='float_L' style="width:125px;">
        	<input type='text' name='class10StartDate' id='class10StartDate'  validate="validateInteger"   required="true"   caption="Class 10th start date"   minlength="4"   maxlength="4" tip="Enter the year when you started your class 10th."   value='' style="width:120px" />
			<?php if(isset($class10StartDate) && $class10StartDate!=""){ ?>
			<script>
                document.getElementById("class10StartDate").value = "<?php echo str_replace("\n", '\n', $class10StartDate );  ?>";
                document.getElementById("class10StartDate").style.color = "";
            </script>
              <?php } ?>
			<div style='display:none'><div class='errorMsg' id= 'class10StartDate_error'></div></div>
		</div>
        
        <div style="float:left; margin-left:20px">
            <label style="width:auto; font-weight:normal">to: </label>
            <div class='float_L'  style="width:125px;">
                <input type='text' name='class10EndDate' id='class10EndDate'  validate="validateInteger"   required="true"   caption="Class 10th end date"   minlength="4"   maxlength="4"  tip="Enter the year when you finished your class 10th."   value='' style="width:120px" />
                <?php if(isset($class10EndDate) && $class10EndDate!=""){ ?>
                <script>
                    document.getElementById("class10EndDate").value = "<?php echo str_replace("\n", '\n', $class10EndDate );  ?>";
                    document.getElementById("class10EndDate").style.color = "";
                </script>
                  <?php } ?>
                <div style='display:none'><div class='errorMsg' id= 'class10EndDate_error'></div></div>
            </div>
        </div>
        
	</li>

	<li>
    	<label style="font-weight:normal">Class 12th duration <strong style="font-weight:normal; margin-left:10px">from</strong>: </label>
        <div class='float_L' style="width:125px;">
        	<input type='text' name='class12StartDate' id='class12StartDate'  validate="validateInteger"   required="true"   caption="Class 12th start date"   minlength="4"   maxlength="4" tip="Enter the year when you started your class 12th."   value=''  style="width:120px;" />
			<?php if(isset($class12StartDate) && $class12StartDate!=""){ ?>
			<script>
                document.getElementById("class12StartDate").value = "<?php echo str_replace("\n", '\n', $class12StartDate );  ?>";
                document.getElementById("class12StartDate").style.color = "";
            </script>
            <?php } ?>
			<div style='display:none'><div class='errorMsg' id= 'class12StartDate_error'></div></div>
		</div>
        <div style="float:left; margin-left:20px">
            <label style="width:auto; font-weight:normal">to: </label>
            <div class='float_L'  style="width:125px;">
                <input type='text' name='class12EndDate' id='class12EndDate'  validate="validateInteger"   required="true"   caption="Class 12th end date"   minlength="4"   maxlength="4" tip="Enter the year when you finished your class 12th."   value=''  style="width:120px;" />
                <?php if(isset($class12EndDate) && $class12EndDate!=""){ ?>
                <script>
                    document.getElementById("class12EndDate").value = "<?php echo str_replace("\n", '\n', $class12EndDate );  ?>";
                    document.getElementById("class12EndDate").style.color = "";
                </script>
                  <?php } ?>
                <div style='display:none'><div class='errorMsg' id= 'class12EndDate_error'></div></div>
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
    	<label style="font-weight:normal"><?php echo $graduationCourseName; ?> duration <strong style="font-weight:normal; margin-left:10px">from</strong>: </label>
        <div class='float_L' style="width:125px;">
        	<input type='text' name='graduationStartDate' id='graduationStartDate'  validate="validateInteger"  required="true"  caption="Graduation start date"   minlength="4"   maxlength="4"     tip="Enter the year when you started your <?php echo $graduationCourseName; ?>"   value='' style="width:120px;" />
			<?php if(isset($graduationStartDate) && $graduationStartDate!=""){ ?>
			<script>
                document.getElementById("graduationStartDate").value = "<?php echo str_replace("\n", '\n', $graduationStartDate );  ?>";
                document.getElementById("graduationStartDate").style.color = "";
            </script>
	      	<?php } ?>
			<div style='display:none'><div class='errorMsg' id= 'graduationStartDate_error'></div></div>
		</div>
        
        <div style="float:left; margin-left:20px">
            <label style="width:auto; font-weight:normal">to: </label>
            <div class='float_L' style="width:125px;"><input type='text' name='graduationEndDate' id='graduationEndDate'  validate="validateInteger"   required="true"   caption="Graduation end date"   minlength="4"   maxlength="4"     tip="Enter the year when you finished your <?php echo $graduationCourseName; ?>"   value='' style="width:120px;" />
			<?php if(isset($graduationYear) && $graduationYear!=""){ ?>
            <script>
                document.getElementById("graduationEndDate").value = "<?php echo str_replace("\n", '\n', $graduationYear );  ?>";
                document.getElementById("graduationEndDate").style.color = "";
            </script>
              <?php } ?>
            <div style='display:none'><div class='errorMsg' id= 'graduationEndDate_error'></div></div>
            </div>
        </div>
	</li>

	<?php
	if(count($otherCourses)>0) {
		foreach($otherCourses as $otherCourseId => $otherCourseName) {
			
			$oStartDate = 'otherCourseStartDate_mul_'.$otherCourseId;
			$oStartDateVal = $$oStartDate;
			$oEndDate = 'otherCourseEndDate_mul_'.$otherCourseId;
			$oEndDateVal = $$oEndDate;
			
			if(!$oEndDateVal) {
				$oEndDateVal = $otherCourseYears[$otherCourseId];
			}
	?>

	<li>
    	<label style="font-weight:normal"><?php echo $otherCourseName; ?> duration <strong style="font-weight:normal; margin-left:10px">from</strong>: </label>
        <div class='float_L' style="width:125px;">
        	<input type='text' name='<?php echo $oStartDate; ?>' id='<?php echo $oStartDate; ?>'  validate="validateInteger"   required="true"   caption="<?php echo $otherCourseName; ?> start date"   minlength="4" maxlength="4" tip="Enter the year when you started your <?php echo $otherCourseName; ?>"   value='' style="width:120px;" />
			<?php if(isset($oStartDateVal) && $oStartDateVal!=""){ ?>
			<script>
                document.getElementById("<?php echo $oStartDate; ?>").value = "<?php echo str_replace("\n", '\n', $oStartDateVal );  ?>";
                document.getElementById("<?php echo $oStartDate; ?>").style.color = "";
            </script>
              <?php } ?>
			<div style='display:none'><div class='errorMsg' id= '<?php echo $oStartDate; ?>_error'></div></div>
		</div>
        <div style="float:left; margin-left:20px">
            <label style="width:auto; font-weight:normal">to: </label>
            <div class='float_L' style="width:125px;">
                <input type='text' name='<?php echo $oEndDate; ?>' id='<?php echo $oEndDate; ?>'  validate="validateInteger"   required="true"   caption="<?php echo $otherCourseName; ?> end date"   minlength="4"   maxlength="4" tip="Enter the year when you finished your <?php echo $otherCourseName; ?>"   value='' style="width:120px;" />
                <?php if(isset($oEndDateVal) && $oEndDateVal!=""){ ?>
                <script>
                    document.getElementById("<?php echo $oEndDate; ?>").value = "<?php echo str_replace("\n", '\n', $oEndDateVal );  ?>";
                    document.getElementById("<?php echo $oEndDate; ?>").style.color = "";
                </script>
                  <?php } ?>
                <div style='display:none'><div class='errorMsg' id= '<?php echo $oEndDate; ?>_error'></div></div>
            </div>
          </div>
	</li>

	<?php
		}
	}
	?>
         <?php endif;?>
	
	
	<li style="width:100%">
		<h3 class="upperCase">Examination Details:</h3>
		<div class='additionalInfoLeftCol' style="width:930px;">
		<label>TESTS: </label>
		<div class='fieldBoxLarge' style="width:590px;">
		<input validate="validateCheckedGroup" required="true" caption="Exams" onClick="checkTestScore(this);" type='checkbox' name='testNamesSkyline[]' id='testNamesSkyline0'   value='CAT'    onmouseover="showTipOnline('Tick the appropriate box and provide the roll number, test date and score (if available)',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Tick the appropriate box and provide the roll number, test date and score (if available)',this);" onmouseout="hidetip();" >CAT</span>&nbsp;&nbsp;
		<input validate="validateCheckedGroup" required="true" caption="Exams" onClick="checkTestScore(this);" type='checkbox' name='testNamesSkyline[]' id='testNamesSkyline1'   value='MAT'     onmouseover="showTipOnline('Tick the appropriate box and provide the roll number, test date and score (if available)',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Tick the appropriate box and provide the roll number, test date and score (if available)',this);" onmouseout="hidetip();" >MAT</span>&nbsp;&nbsp;
		
		<input validate="validateCheckedGroup" required="true" caption="Exams" onClick="checkTestScore(this);" type='checkbox' name='testNamesSkyline[]' id='testNamesSkyline3'   value='XAT'     onmouseover="showTipOnline('Tick the appropriate box and provide the roll number, test date and score (if available)',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Tick the appropriate box and provide the roll number, test date and score (if available)',this);" onmouseout="hidetip();" >XAT</span>&nbsp;&nbsp;

                <input validate="validateCheckedGroup" required="true" caption="Exams" onClick="checkTestScore(this);" type='checkbox' name='testNamesSkyline[]' id='testNamesSkyline2'   value='CMAT'     onmouseover="showTipOnline('Tick the appropriate box and provide the roll number, test date and score (if available)',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Tick the appropriate box and provide the roll number, test date and score (if available)',this);" onmouseout="hidetip();" >CMAT</span>&nbsp;&nbsp;
		
		<?php if(isset($testNamesSkyline) && $testNamesSkyline!=""){ ?>
		<script>
		    objCheckBoxes = document.forms["OnlineForm"].elements["testNamesSkyline[]"];
		    var countCheckBoxes = objCheckBoxes.length;
		    for(var i = 0; i < countCheckBoxes; i++){
			      objCheckBoxes[i].checked = false;

			      <?php $arr = explode(",",$testNamesSkyline);
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
		
		<div style='display:none'><div class='errorMsg' id= 'testNamesSkyline_error'></div></div>
		</div>
		</div>
	</li>

	<li id="cat1" style="display:none;">

		<div class='additionalInfoLeftCol'>
		<label>CAT Date of Examination: </label>
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

		<div class='additionalInfoRightCol'>
		<label>CAT Score: </label>
		<div class='fieldBoxLarge'>
		<input type='text' name='catScoreAdditional' id='catScoreAdditional'   validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"     tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter <b>NA.</b>"  allowNA="true"   value=''  />
		<?php if(isset($catScoreAdditional) && $catScoreAdditional!=""){ ?>
		  <script>
		      document.getElementById("catScoreAdditional").value = "<?php echo str_replace("\n", '\n', $catScoreAdditional );  ?>";
		      document.getElementById("catScoreAdditional").style.color = "";
		  </script>
		<?php } ?>
		
		<div style='display:none'><div class='errorMsg' id= 'catScoreAdditional_error'></div></div>
		</div>
		</div>
	</li>

	<li id="cat2" style="display:none; border-bottom:1px dotted #c0c0c0; padding-bottom:15px">
		<div class='additionalInfoLeftCol'>
		<label>CAT Roll No.: </label>
		<div class='fieldBoxLarge'>
		<input class="textboxLarge" type='text' name='catRollNumberAdditional' id='catRollNumberAdditional'  validate="validateStr"    caption="roll no"   minlength="2"   maxlength="50"        tip="Mention your Roll number for the exam."   value=''   />
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
		<label>CAT Percentile: </label>
		<div class='fieldBoxLarge'>
		<input type='text' name='catPercentileAdditional' id='catPercentileAdditional'   validate="validateFloat" allowNA="true"  caption="Percentile"   minlength="1"   maxlength="5"      tip="Mention your percentile in the exam. If you don't know your percentile, enter <b>NA.</b>"   value=''  />
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
	    if(isset($testNamesSkyline) && $testNamesSkyline!="" && strpos($testNamesSkyline,'CAT')!==false){ ?>
	    <script>
		    checkTestScore(document.getElementById('testNamesSkyline0'));
	    </script>
	<?php
	    }
	?>
	

	<li id="mat1" style="display:none;">
		<div class='additionalInfoLeftCol'>
		<label>MAT Date of Examination: </label>
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

		<div class='additionalInfoRightCol'>
		<label>MAT Score: </label>
		<div class='fieldBoxLarge'>
		<input type='text' name='matScoreAdditional' id='matScoreAdditional'  validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"         tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter <b>NA.</b>"   value=''  allowNA="true" />
		<?php if(isset($matScoreAdditional) && $matScoreAdditional!=""){ ?>
		  <script>
		      document.getElementById("matScoreAdditional").value = "<?php echo str_replace("\n", '\n', $matScoreAdditional );  ?>";
		      document.getElementById("matScoreAdditional").style.color = "";
		  </script>
		<?php } ?>
		
		<div style='display:none'><div class='errorMsg' id= 'matScoreAdditional_error'></div></div>
		</div>
		</div>
		
	</li>

	<li id="mat2" style="display:none; border-bottom:1px dotted #c0c0c0; padding-bottom:15px">
		<div class='additionalInfoLeftCol'>
		<label>MAT Roll No.: </label>
		<div class='fieldBoxLarge'>
		<input class="textboxLarge" type='text' name='matRollNumberAdditional' id='matRollNumberAdditional'     validate="validateStr"    caption="roll no"   minlength="2"   maxlength="50"   tip="Mention your Roll number for the exam."    value=''   />
		<?php if(isset($matRollNumberAdditional) && $matRollNumberAdditional!=""){ ?>
		  <script>
		      document.getElementById("matRollNumberAdditional").value = "<?php echo str_replace("\n", '\n', $matRollNumberAdditional );  ?>";
		      document.getElementById("matRollNumberAdditional").style.color = "";
		  </script>
		<?php } ?>
		
		<div style='display:none'><div class='errorMsg' id= 'matRollNumberAdditional_error'></div></div>
		</div>
		</div>
	
		<div class="additionalInfoRightCol">
		<label>MAT Percentile: </label>
		<div class='float_L'>
		   <input class="textboxSmaller"  type='text' name='matPercentileAdditional' id='matPercentileAdditional'  validate="validateFloat" allowNA="true"  caption="Percentile"   minlength="1"   maxlength="5"     tip="Mention your percentile in the exam. If you don't know your percentile, enter <b>NA.</b>"   value=''  />
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
	    if(isset($testNamesSkyline) && $testNamesSkyline!=""){ 
		    $tests = explode(',',$testNamesSkyline);
		    foreach ($tests as $test){
			  if($test=='MAT'){
	    ?>
	    <script>
		    checkTestScore(document.getElementById('testNamesSkyline1'));
	    </script>
	<?php }
	      }
	    }
	?>


	

	<li id="xat1" style="display:none;">
		<div class='additionalInfoLeftCol'>
		<label>XAT Date of Examination: </label>
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

		<div class='additionalInfoRightCol'>
		<label>XAT Score: </label>
		<div class='fieldBoxLarge'>
		<input type='text' name='xatScoreAdditional' id='xatScoreAdditional'  validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"         tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter <b>NA.</b>"   value=''  allowNA="true" />
		<?php if(isset($xatScoreAdditional) && $xatScoreAdditional!=""){ ?>
		  <script>
		      document.getElementById("xatScoreAdditional").value = "<?php echo str_replace("\n", '\n', $xatScoreAdditional );  ?>";
		      document.getElementById("xatScoreAdditional").style.color = "";
		  </script>
		<?php } ?>
		
		<div style='display:none'><div class='errorMsg' id= 'xatScoreAdditional_error'></div></div>
		</div>
		</div>
	</li>

	<li id="xat2" style="display:none; border-bottom:1px dotted #c0c0c0; padding-bottom:15px">
		<div class='additionalInfoLeftCol'>
		<label>XAT Roll No.: </label>
		<div class='fieldBoxLarge'>
		<input class="textboxLarge" type='text' name='xatRollNumberAdditional' id='xatRollNumberAdditional'   tip="Mention your Roll number for the exam."    validate="validateStr"    caption="roll no"   minlength="2"   maxlength="50"     value=''   />
		<?php if(isset($xatRollNumberAdditional) && $xatRollNumberAdditional!=""){ ?>
		  <script>
		      document.getElementById("xatRollNumberAdditional").value = "<?php echo str_replace("\n", '\n', $xatRollNumberAdditional );  ?>";
		      document.getElementById("xatRollNumberAdditional").style.color = "";
		  </script>
		<?php } ?>
		
		<div style='display:none'><div class='errorMsg' id= 'xatRollNumberAdditional_error'></div></div>
		</div>
		</div>
		
		<div class="additionalInfoRightCol">
		<label>XAT Percentile: </label>
		<div class='float_L'>
		   <input class="textboxSmaller"  type='text' name='xatPercentileAdditional' id='xatPercentileAdditional'  validate="validateFloat" allowNA="true"  caption="Percentile"   minlength="1"   maxlength="5"     tip="Mention your percentile in the exam. If you don't know your percentile, enter <b>NA.</b>"   value=''  />
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
	    if(isset($testNamesSkyline) && $testNamesSkyline!="" && strpos($testNamesSkyline,'XAT')!==false){ ?>
	    <script>
		    checkTestScore(document.getElementById('testNamesSkyline3'));
	    </script>
	<?php
	    }
	?>



    <li id="cmat1" style="display:none;">

		<div class='additionalInfoLeftCol'>
		<label>CMAT Date of Examination: </label>
		<div class='fieldBoxLarge'>
		<input type='text' name='cmatDateOfExaminationAdditional' id='cmatDateOfExaminationAdditional' readonly maxlength='10'    validate="validateDateForms"  caption="date"      tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('cmatDateOfExaminationAdditional'),'cmatDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />
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

		<div class='additionalInfoRightCol'>
		<label>CMAT Score: </label>
		<div class='fieldBoxLarge'>
		<input type='text' name='cmatScoreAdditional' id='cmatScoreAdditional'   validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"     tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter <b>NA.</b>"  allowNA="true"   value=''  />
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

	<li id="cmat2" style="display:none; border-bottom:1px dotted #c0c0c0; padding-bottom:15px">
		<div class='additionalInfoLeftCol'>
		<label>CMAT Roll No.: </label>
		<div class='fieldBoxLarge'>
		<input class="textboxLarge" type='text' name='cmatRollNumberAdditional' id='cmatRollNumberAdditional'  validate="validateStr"    caption="roll no"   minlength="2"   maxlength="50"        tip="Mention your Roll number for the exam."   value=''   />
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
				<label>CMAT Rank: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='cmatRankAdditional' id='cmatRankAdditional'  validate="validateInteger"    caption="Rank"   minlength="1"   maxlength="7"     tip="Mention your Rank in the exam. If you don't know your Rank, enter <b>NA.</b>"   value=''   allowNA='true' />
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
	<?php
	    if(isset($testNamesSkyline) && $testNamesSkyline!="" && strpos($testNamesSkyline,'CMAT')!==false){ ?>
	    <script>
		    checkTestScore(document.getElementById('testNamesSkyline2'));
	    </script>
	<?php
	    }
	?>
	
	
	
	
	<?php if($action != 'updateScore'):?>
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
	
	if(count($workCompanies) > 0) {
		$j = 0;
		foreach($workCompanies as $workCompanyKey => $workCompany) {
			$doa = 'dateOfAppointment'.$workCompanyKey;
			$doaVal = $$doa;
			$aoe = 'addressOfEmployer'.$workCompanyKey;
			$aoeVal = $$aoe;
			$eemail = 'employerEmailId'.$workCompanyKey;
			$eemailVal = $$eemail;
			$etn = 'employerTelephoneNumber'.$workCompanyKey;
			$etnVal = $$etn;
			$efn = 'employerFaxNumber'.$workCompanyKey;
			$efnVal = $$efn;
			$j++;

	?>

	
	<li>
		<?php if($j==1){ ?><h3 class="upperCase">Additional Employer Details:</h3><?php } ?>
		<label style="font-weight:normal"><?php echo $workCompany; ?> Date of appointment: </label>
		<div class='float_L'>
        	<input style="width:120px" type='text' name='<?=$doa?>' id='<?=$doa?>' readonly  validate="validateStr" caption="Date of appointment"   minlength="2"
		maxlength="15"   onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('<?=$doa?>'),'<?=$doa?>_dateImg','dd/MM/yyyy');" />&nbsp;
		<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='<?=$doa?>_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('<?=$doa?>'),'<?=$doa?>_dateImg','dd/MM/yyyy'); return false;" />
			<?php if(isset($doaVal) && $doaVal!=""){ ?>
			<script>
                document.getElementById("<?=$doa?>").value = "<?php echo str_replace("\n", '\n', $doaVal );  ?>";
                document.getElementById("<?=$doa?>").style.color = "";
            </script>
	      <?php } ?>
			<div style='display:none'><div class='errorMsg' id= '<?=$doa?>_error'></div></div>
		</div>
	</li>

	<li>    	
		<div class="additionalInfoLeftCol">
		    <label><?php echo $workCompany; ?> Address of employer: </label>
		    <div class='fieldBoxLarge'>
			<textarea name='<?=$aoe?>' id='<?=$aoe?>'  validate="validateStr"   required="true"   caption="Address of employer"   minlength="2"   maxlength="200"     tip="Enter the address of the employer. <?php echo $NAText; ?>"  class="textboxLarge" ></textarea>
			<?php if(isset($aoeVal) && $aoeVal!=""){ ?>
			<script>
			    document.getElementById("<?=$aoe?>").value = "<?php echo str_replace("\n", '\n', $aoeVal );  ?>";
			    document.getElementById("<?=$aoe?>").style.color = "";
			</script>
			  <?php } ?>
			<div style='display:none'><div class='errorMsg' id= '<?=$aoe?>_error'></div></div>
		    </div>
		</div>
		<div class="additionalInfoRightCol">
		    <label><?php echo $workCompany; ?> Email address: </label>
		    <div class='fieldBoxLarge'>
			<input type='text' name='<?=$eemail?>' id='<?=$eemail?>'  validate="validateEmail"   required="true" allowNA="true"  caption="Employer email id"   minlength="2"   maxlength="200"     tip="Enter the email address of the employer. <?php echo $NAText; ?>"   value='' class="textboxLarge" />
		    <?php if(isset($eemailVal) && $eemailVal!=""){ ?>
		    <script>
			document.getElementById("<?=$eemail?>").value = "<?php echo str_replace("\n", '\n', $eemailVal );  ?>";
			document.getElementById("<?=$eemail?>").style.color = "";
		    </script>
		      <?php } ?>
		    <div style='display:none'><div class='errorMsg' id= '<?=$eemail?>_error'></div></div>
		    </div>
		</div>       
	</li>

	<li>
		<div class="additionalInfoLeftCol">
		    <label><?php echo $workCompany; ?> Telephone number: </label>
		    <div class="fieldBoxLarge">
			<input type='text' name='<?=$etn?>' id='<?=$etn?>'  validate="validateStr"   required="true"   caption="Employer telephone number"   minlength="2" maxlength="15"     tip="Enter the telephone number of the employer. <?php echo $NAText; ?>"   value='' class="textboxLarge" />
			<?php if(isset($etnVal) && $etnVal!=""){ ?>
			<script>
			    document.getElementById("<?=$etn?>").value = "<?php echo str_replace("\n", '\n', $etnVal);  ?>";
			    document.getElementById("<?=$etn?>").style.color = "";
			</script>
			  <?php } ?>
			<div style='display:none'><div class='errorMsg' id= '<?=$etn?>_error'></div></div>
		    </div>
		 </div>
		 
		 <div class="additionalInfoRightCol">
			<label><?php echo $workCompany; ?> Fax: </label>
		    <div class='fieldBoxLarge'>
			<input type='text' name='<?=$efn?>' id='<?=$efn?>'  validate="validateStr"   required="true"   caption="Employer fax number"   minlength="2"   maxlength="15" tip="Enter the fax number of the employer. <?php echo $NAText; ?>"   value='' class="textboxLarge" />
			<?php if(isset($efnVal) && $efnVal!=""){ ?>
			<script>
			    document.getElementById("<?=$efn?>").value = "<?php echo str_replace("\n", '\n', $efnVal );  ?>";
			    document.getElementById("<?=$efn?>").style.color = "";
			</script>
		      <?php } ?>
			<div style='display:none'><div class='errorMsg' id= '<?=$efn?>_error'></div></div>
		    </div>
		 </div>
	</li>

	<?php }} ?>

    <?php if($j>0){ ?>
	<li>
		<label style="font-weight:normal">Total work experience (in years): </label>
		<div class='float_L'>
        	<input type='text' name='totalYearsOfExperience' id='totalYearsOfExperience'  validate="validateFloat"   required="true" allowNA="true" size="5"  caption="Total work experience"   minlength="1"   maxlength="4"     tip="Enter the total work experience in years. <?php echo $NAText; ?>"   value='' /> Yrs
		<?php if(isset($totalYearsOfExperience) && $totalYearsOfExperience!=""){ ?>
		<script>
		    document.getElementById("totalYearsOfExperience").value = "<?php echo str_replace("\n", '\n', $totalYearsOfExperience );  ?>";
		    document.getElementById("totalYearsOfExperience").style.color = "";
		</script>
	      <?php } ?>
		<div style='display:none'><div class='errorMsg' id= 'totalYearsOfExperience_error'></div></div>
		</div>
	</li>
    <?php } ?>
	
	<li>
    	<h3 class="upperCase">Others</h3>
        <div class="clearFix"></div>
    	<label style="font-weight:normal">Please state briefly why you want to join this course: </label>
        <div class='float_L'>
        	<textarea name='whyJoinCourse' id='whyJoinCourse'  validate="validateStr"   required="true"   caption="Reason"   minlength="2"   maxlength="1500"     tip="Please write a short essay on why you wish to join this course, how will it help your career etc."  style="width:613px" ></textarea>
			<?php if(isset($whyJoinCourse) && $whyJoinCourse!=""){ ?>
		<script>
		    document.getElementById("whyJoinCourse").value = "<?php echo str_replace("\n", '\n', $whyJoinCourse );  ?>";
		    document.getElementById("whyJoinCourse").style.color = "";
		</script>
	     <?php } ?>
		<div style='display:none'><div class='errorMsg' id= 'whyJoinCourse_error'></div></div>
		</div>
	</li>

	<li>
    	<label style="font-weight:normal">Please state briefly why you want to join skyline business school: </label>
        <div class='float_L'>
        	<textarea name='whyJoinSkyline' id='whyJoinSkyline'  validate="validateStr"   required="true"   caption="Reason"   minlength="2"   maxlength="1500"     tip="Please write a short essay on why you wish to join Skyline business school."  style="width:613px" ></textarea>
			<?php if(isset($whyJoinSkyline) && $whyJoinSkyline!=""){ ?>
			<script>
                document.getElementById("whyJoinSkyline").value = "<?php echo str_replace("\n", '\n', $whyJoinSkyline );  ?>";
                document.getElementById("whyJoinSkyline").style.color = "";
            </script>
              <?php } ?>
			<div style='display:none'><div class='errorMsg' id= 'whyJoinSkyline_error'></div></div>
		</div>
	</li>




        


	<li>
    	<label style="font-weight:normal">Sources of information about skyline: </label>
        <div class='float_L'>
        	<input type='text' name='sourceOfInformation' id='sourceOfInformation'  validate="validateStr"   required="true"   caption="Source of information"   minlength="2"   maxlength="200" tip="Please enter where you heard about skyline business school. For example friends, advertisement, internet etc."   value='' class="textboxLarger" />
			<?php if(isset($sourceOfInformation) && $sourceOfInformation!=""){ ?>
			<script>
                document.getElementById("sourceOfInformation").value = "<?php echo str_replace("\n", '\n', $sourceOfInformation );  ?>";
                document.getElementById("sourceOfInformation").style.color = "";
            </script>
              <?php } ?>
			<div style='display:none'><div class='errorMsg' id= 'sourceOfInformation_error'></div></div>
		</div>
	</li>
	
	
	
<li>
				<h3 class=upperCase'>Centres for group discussion and personal interview</h3>
				<label style='font-weight:normal'>Preferred GD/PI Location: </label>
				<div class='fieldBoxLarge'>
				<select name='preferredGDPILocation' id='preferredGDPILocation' onmouseover="showTipOnline('Select your preferred GD/PI location.',this);" onmouseout='hidetip();'  validate="validateSelect"  minlength="1"   maxlength="1500"  required="true" caption="Preferred GD/PI location" onchange="selectGD('Skyline_GDPI1_Date',this);">
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
			        
                                   <div id= "Skyline_GDPI1_Date_d" class='additionalInfoRightCol' style="display:none">
                                <label>GD/PI Date: </label>
                                <div class='fieldBoxLarge'>
                                <select name='Skyline_GDPI1_Date' id='Skyline_GDPI1_Date'      validate="validateSelect"   required="true" onmouseover="showTipOnline('Select your preferred GD/PI location date.',this);" onmouseout='hidetip();'   caption="GD/PI location date"  >
				<option value="Select"> Select</option>
                                </select>
                                <?php if(isset($Skyline_GDPI1_Date) && $Skyline_GDPI1_Date!=""){ ?>
                              <script>
                                  var selObj = document.getElementById("Skyline_GDPI1_Date"); 
				  selectGD("Skyline_GDPI1_Date",document.getElementById('preferredGDPILocation'));      
                                  var A= selObj.options, L= A.length;
                                  while(L){
                                      if (A[--L].value== "<?php echo $Skyline_GDPI1_Date;?>"){
                                          selObj.selectedIndex= L;
                                          L= 0;
                                      }
                                  } 
                              </script>
                            <?php } ?>

                                <div style='display:none'><div class='errorMsg' id= 'Skyline_GDPI1_Date_error'></div></div>
                                </div>
                                </div>
		</li>
        

	<li>
		<label style="font-weight:normal">Terms:</label>
		<div class='float_L' style="width:620px; color:#666666; font-style:italic">
		I <student_first_name> <middle_name> <last_name> hereby certify that the information stated in this application by me is true and correct to the best of my knowledge and that nothing has been concealed therein. I have read and understood the conditions mentioned clearly. The degree/diploma certificate will be awarded to me only on the successful completion of the course requirements and examination of the courses applied for. Only enrolling for the course does not make me eligible for the Award.<br /><br/>
I understand that in case any information submitted by me is found to be incorrect or not fully furnished by original documents at any point of time, either during the course, or after the completion of the relevant course, it would lead to cancellation of enrolment/dropping my name from the mentioned course without any fee refund. Furthermore, once I am enrolled in the course, I declare that I shall continue to attend and pay the fee as mentioned in the specific fee structure.
		
		<div class="spacer10 clearFix"></div>
       	<div >
        	<input type='checkbox' name='agreeToTermsSkyline[]' id='agreeToTermsSkyline0' value='1' required="true" validate="validateChecked" caption="Please agree to the terms stated above"></input>&nbsp;&nbsp;
			I agree to the terms stated above
			<?php if(isset($agreeToTermsSkyline) && $agreeToTermsSkyline!=""){ ?>
				<script>
		    //objCheckBoxes = document.forms["OnlineForm"].elements["agreeToTermsSkyline[]"];
			objCheckBoxes = document.getElementsByName("agreeToTermsSkyline[]");
		    var countCheckBoxes = objCheckBoxes.length;
			
		    for(var i = 0; i < countCheckBoxes; i++){
			      objCheckBoxes[i].checked = false;

			      <?php $arr = explode(",",$agreeToTermsSkyline);
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
		<div style='display:none'><div class='errorMsg' id= 'agreeToTermsSkyline0_error'></div></div>
		</div>
	</div>
	</li>
        <?php endif;?>
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

  </script>
