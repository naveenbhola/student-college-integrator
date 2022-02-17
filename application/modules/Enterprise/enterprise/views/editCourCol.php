<!--Edit_college_course_form_start-->
<?php  
   $attribute = array('name' => 'courseListing','id'=> 'courseListing', 'method' => 'post');
   echo form_open_multipart('enterprise/Enterprise/updateCourseCMS',$attribute); 
?>
<?php 
    $style = "display:block";
    global $formFlow;
    global $maxVideos;
    global $maxPhotos;
    global $maxDocs;
    global $featuredLogo;
    global $featuredPanel;

    $formFlow = 'edit';
    $maxVideos =3;
    $maxPhotos =3;
    $maxDocs =3;
    $featuredLogo = "Yes";
    $featuredPanel ="Yes";

?>
<!-- <input type="hidden" name="compCatTree" id="compCatTree" value=' <?php echo $completeCategoryTree; ?> ' /> -->
<script>
   var completeCategoryTree = eval(<?php echo $completeCategoryTree; ?>);
   var cal = new CalendarPopup("calendardiv");
   cal.offsetX = 20;
   cal.offsetY = 0;
</script>

<div style="display:none;">
<?php 
        $this->load->view('listing/packSelection');
    ?>
</div>

<input type="hidden" name="update_course_id" value="<?php echo $course_id; ?>" />
<input type="hidden" name="old_institute_id" value="<?php echo $institute_id; ?>" />
<input type="hidden" id="listingProdId" name="listingProdId" value="<?php echo $packType; ?>" />

<div class="row">
   <div class="mar_left_5p"><span class="normaltxt_11p_blk fontSize_13p OrgangeFont bld">Edit Course Details</span></div>			
   <div class="grayLine mar_top_5p"></div>	
</div>
   <div class="mar_left_5p">This course belongs to institute named: <span class="normaltxt_11p_blk bld"><?php echo $institute_name; ?></span></div>			

<div class="lineSpace_25">&nbsp;</div>
<div class="row">	
   <div style="display: inline; float:left; width:100%">
      <div class="r1 bld">&nbsp;</div>
      <div class="r2">All field marked with <span class="redcolor">*</span> are compulsory to fill in</div>
   </div>
</div>
<div class="lineSpace_25">&nbsp;</div>

<div class="row">	
   <div>
      <div>
         <div class="r1 bld">Course Name:<span class="redcolor">*</span>&nbsp;</div>
         <div class="r2">
            <input type="text" name="c_course_title" id="c_course_title" validate="validateStr" maxlength="100" minlength="1" tip="course_title" class="w62_per" required="true" caption="Course Title" value="<?php echo $title ;?>"/>
         </div>
         <div class="clear_L"></div>
      </div>
      <div class="row errorPlace">
         <div class="r1">&nbsp;</div>
         <div class="r2 errorMsg" id="c_course_title_error" ></div>
         <div class="clear_L"></div>
      </div>
   </div>
</div>
<div class="lineSpace_13">&nbsp;</div>

<div class="row">	
   <div>
      <div>
          <div class="r1 bld">Level:&nbsp;</div>
         <div class="r2">
             <?php $course_level_arr = array("Select Level"=>"","Under Graduate Degree"=>"Under Graduate Degree","Post Graduate Degree"=>"Post Graduate Degree","Post Graduate Diploma"=>"Post Graduate Diploma","Doctrate/PhD"=>"Doctrate/PhD","Post Doctrate/ Post PhD"=>"Post Doctrate/ Post PhD","Certification"=>"Certification","Diploma [Others]"=>"Diploma [Others]","Other"=>"Other");
                 if(($course_level == NULL) || array_key_exists($course_level,$course_level_arr)){
                  $Other_Flag = 0;
               }else{
                  $Other_Flag = 1;
               }
            ?>
            <select id="course_level" name="course_level" style="width:200px" onChange="checkCourseLevel();" tip="course_level" >
               <?php 
                   foreach($course_level_arr as $key => $val)
                  {
                     if($Other_Flag == 0){
                         if($key == $course_level){
                           echo '<option value="'.$val.'" selected>'.$key.'</option>';
                        }else{
                           echo '<option value="'.$val.'">'.$key.'</option>';
                        }
                     }
                     if($Other_Flag == 1){
                        if($key != "Other"){
                           echo '<option value="'.$val.'">'.$key.'</option>';
                        }else{
                           echo '<option value="'.$val.'" selected>'.$key.'</option>';
                        }
                     }
                  }
               ?>
            </select>
            <?php
               if($Other_Flag == 0){
                   echo '<input type="text" name="other_course_level" id="other_course_level" style="display:none" />';
               }else if ($Other_Flag ==1){
                   echo '<input type="text" name="other_course_level" id="other_course_level" style="" value="'.$course_level.'" />';
               }
            ?>
         </div>
         <div class="clear_L"></div> 
      </div>
      <div class="row errorPlace">
         <div class="r1">&nbsp;</div>
         <div class="r2 errorMsg" id="course_level_error" ></div>
         <div class="clear_L"></div>
      </div>
   </div>
</div>
<div class="lineSpace_13">&nbsp;</div>


<div class="row">
   <div>
      <div>
	 <div class="r1 bld">Type of Course:&nbsp;</div>
	 <div class="r2">
             <?php $course_type_arr = array("Select Type"=>"","Exam Preparation"=>"Exam Preparation","Part time"=>"Part time","Full time"=>"Full time","Correspondence course"=>"Correspondence course");
             ?>
             <select id="course_type" name="course_type" style="width:200px" tip="course_type"  onchange="checkForExamPrep(this);" >
                 <?php
                     foreach($course_type_arr as $key => $val)
                     {
                         if($key == $course_type){
                             echo '<option value="'.$val.'" selected>'.$key.'</option>';
                         }else{
                             echo '<option value="'.$val.'">'.$key.'</option>';
                         }
                     }
                   ?>
	    </select>
	 </div>
	 <div class="clear_L"></div>
      </div>
      <div class="row errorPlace">
	 <div class="r1">&nbsp;</div>
	 <div class="r2 errorMsg" id="course_type_error" ></div>
	 <div class="clear_L"></div>
      </div>
   </div>
</div>

<div id="examPrepContent" style="display:none;">
<div class="lineSpace_13">&nbsp;</div>
<?php
    $examSelectPanelAttribs = array('examSelectComboName'=>'examPrepRelatedExams', 'examSelectComboCaption'=>'Coaching for Exams', 'examSelected' => $tests_preparation, 'otherExam'=>($tests_preparation_other!='[]' ? $tests_preparation_other : ''));
$this->load->view('common/examSelectPanel', $examSelectPanelAttribs);
?>
</div>
<script>
function checkForExamPrep(courseTypeObj) {
    if(!courseTypeObj) { return; }
    if(courseTypeObj.value === 'Exam Preparation') {
        document.getElementById('examPrepContent').style.display = 'block';
    } else {
        document.getElementById('examPrepContent').style.display = 'none';
    }
}
checkForExamPrep(document.getElementById('course_type'));
</script>
<div class="lineSpace_13">&nbsp;</div>


<div class="row">
   <div>
      <div>
         <div class="r1 bld">Category:<span class="redcolor">*</span>&nbsp;</div>
         <div class="r2">
            <div id="c_categories_combo"></div>
            <script>
               var selectCatArr = new Array();
               <?php
                  $i=0;
                  foreach($categoryArr as $existingCateg)
                  {?>
                  selectCatArr[<?php echo $i;?>]="<?php echo $existingCateg["category_id"];?>";
                  <?php
                     $i++;
                  } 
               ?>
            </script>
         </div>
         <div class="clear_L"></div>
      </div>
      <div class="row errorPlace">
         <div class="r1">&nbsp;</div>
         <div class="r2 errorMsg" id="c_categories_error" ></div>
         <div class="clear_L"></div>
      </div>
   </div>
</div>
<div class="lineSpace_13">&nbsp;</div>

<div class="row">
   <div>
      <div>
         <div class="r1 bld">Course Description:&nbsp;</div>
         <div class="r2">
            <!-- <textarea type="text" name="c_overview" id="c_overview" validate="validateStr" maxlength="5000" class="w62_per mceEditor" style="height:130px" /><?php echo $overview ;?></textarea> -->
            <textarea type="text" name="c_overview" id="c_overview" maxlength="5000" class="w62_per mceEditor" style="height:130px" validate="validateStr" /><?php echo $overview ;?></textarea>
         </div>
         <div class="clear_L"></div>
      </div>
      <div class="row errorPlace">
         <div class="r1">&nbsp;</div>
         <div class="r2 errorMsg" id="c_overview_error" ></div>
         <div class="clear_L"></div>
      </div>
   </div>
</div>
<div class="lineSpace_13">&nbsp;</div>

<div class="row">	
   <div>
      <div>
         <div class="r1 bld">Course Duration:<span class="redcolor">*</span>&nbsp;</div>
         <div class="r2">
            <input type="text" id="c_duration1" name="c_duration1" validate="validateStr" maxlength="5" minlength="1" class="mar_right_1p" style="width:120px" tip="course_duration" required="true" caption="Course Duration" value="<?php $numDur = explode(" ",$duration); echo $numDur['0']; ?>"/>
            <select id="c_duration2" name="c_duration2">
               <?php $durationTypes = array('Years','Months','Weeks','Days','Hours');
                  foreach($durationTypes as $dtype)
                  {
                     if(trim($numDur['1'])== $dtype)
                     {
                        echo '<option value="'.$dtype.'" selected>'.$dtype.'</option>';
                     }else{
                        echo '<option value="'.$dtype.'">'.$dtype.'</option>';
                     }
                  }
               ?>
            </select>
         </div>
         <div class="clear_L"></div>
      </div>
      <div class="row errorPlace">
         <div class="r1">&nbsp;</div>
         <div class="r2 errorMsg" id="c_duration1_error" ></div>
         <div class="clear_L"></div>
      </div>
   </div>
</div>
<div class="lineSpace_13">&nbsp;</div>

<?php if ($usergroup == "cms"){ ?>
<div class="row">
   <div>
      <div>
	 <div class="r1 bld">Tags:&nbsp;</div>
	 <div class="r2">
	    <input type="text" name="c_course_tags" id="c_course_tags" validate="validateStr" maxlength="200" minlength="2" tip="course_tags" class="w62_per" caption="Course Tags" value="<?php echo $hiddenTags ;?>"/>
	 </div>
	 <div class="clear_L"></div>
      </div>
      <div class="row errorPlace">
	 <div class="r1">&nbsp;</div>
	 <div class="r2 errorMsg" id="c_course_tags_error" ></div>
	 <div class="clear_L"></div>
      </div>
   </div>
</div>
<div class="lineSpace_13">&nbsp;</div>
<?php } ?>
<div class="row">	
   <div>
      <div>
         <div class="r1 bld">Course Start Date:&nbsp;</div>
         <div class="r2">
            <?php $startDate = explode(" ",$start_date); ?>
            <input type="text" name="course_start_date" id="course_start_date" style="width:175px" readonly onClick="cal = new CalendarPopup('calendardiv');cal.select($('course_start_date'),'sd','yyyy-MM-dd');" value="<?php if($startDate[0]!='0000-00-00'){ echo $startDate[0];}else{echo '';} ?>" />
            <img src="/public/images/eventIcon.gif" style="cursor:pointer" align="absmiddle" id="sd" onClick="cal = new CalendarPopup('calendardiv');cal.select($('course_start_date'),'sd','yyyy-MM-dd');" />
         </div>
         <div class="clear_L"></div>
      </div>
      <div class="row errorPlace">
         <div class="r1">&nbsp;</div>
         <div class="r2 errorMsg" id="course_start_date_error"></div>
         <div class="clear_L"></div>
      </div>
   </div>
</div>
<div class="lineSpace_13">&nbsp;</div>

<div class="row">
   <div>
      <div>
         <div class="r1 bld">Course End Date:&nbsp;</div>
         <div class="r2">
            <?php $endDate = explode(" ",$end_date); ?>
            <input type="text" name="course_end_date" id="course_end_date" style="width:175px" onClick="if(fillStartDateFirst()){cal = new CalendarPopup('calendardiv');disableDatesTill(cal,document.getElementById('course_start_date').value);cal.select($('course_end_date'),'ed','yyyy-MM-dd');}" value="<?php  if($endDate[0]!='0000-00-00'){ echo $endDate[0];}else{echo '';} ?>" /> 
            <img src="/public/images/eventIcon.gif" style="cursor:pointer" align="absmiddle" id="ed" onClick="if(fillStartDateFirst()){cal = new CalendarPopup('calendardiv');disableDatesTill(cal,document.getElementById('course_start_date').value);cal.select($('course_end_date'),'ed','yyyy-MM-dd');}" />
         </div>
         <div class="clear_L"></div>
      </div>
      <div class="row errorPlace">
         <div class="r1">&nbsp;</div>
         <div class="r2 errorMsg" id="course_end_date_error"></div>
         <div class="clear_L"></div>
      </div>
   </div>
</div>
<div class="lineSpace_13">&nbsp;</div>

<div class="row">
   <div>
      <div>
         <div class="r1 bld">Course Fees:&nbsp;</div>
         <div class="r2">
            <input type="text" id="c_fees_amount" name="c_fees_amount" validate="validateStr" maxlength="20" minlength="0" class="mar_right_1p" style="width:95px" tip="course_fees" caption="Course Fees" value="<?php
               $numFees = explode(';',$fees,2);
               $feesVal = explode(" ",$numFees[0]);
               if($feesVal != NULL){
                       $feesCurrency = $feesVal[0];
                       $feesNum = $feesVal[1];
                       echo $feesNum;
                    }
            ?>"/>
            <select id="c_fees_currency" name="c_fees_currency">
               <?php $currencyTypes = array('INR','USD','AUD','CAD','SGD','GBP','NZD');
                  foreach($currencyTypes as $ctype)
                  {
                     if(trim($feesCurrency)== $ctype)
                     {
                        echo '<option value="'.$ctype.'" selected>'.$ctype.'</option>';
                     }else{
                        echo '<option value="'.$ctype.'">'.$ctype.'</option>';
                     }
                  }
               ?>
            </select>
         </div>
         <div class="clear_L"></div>
      </div>
      <div class="row errorPlace">
         <div class="r1">&nbsp;</div>
         <div class="errorMsg" id="c_fees_amount_error"></div>
         <div class="clear_L"></div>
      </div>
   </div>
</div>
<div class="lineSpace_13">&nbsp;</div>

<div class="row">
   <div>
      <div>
         <div class="r1 bld">&nbsp;</div>
         <div class="r2">
            <?php
               if($numFees[1]!= NULL)
               {
                  echo '<input type="checkbox" id="chk_fees_other" name="chk_fees_other" checked="checked" onclick="showHideElement(\'c_fees_other\');" />Other<br />
                  <textarea id="c_fees_other" name="c_fees_other" style="" validate="validateStr" maxlength="500" minlength="0" class="w62_per" caption="Fees" >'.$numFees[1].'</textarea>';
               }else{
                  echo '<input type="checkbox" id="chk_fees_other" name="chk_fees_other" onclick="showHideElement(\'c_fees_other\');" />Other<br />
                  <textarea id="c_fees_other" name="c_fees_other" style="display:none" validate="validateStr" maxlength="500" minlength="0" class="w62_per" caption="Fees" ></textarea>';
               }
            ?>
         </div>
         <div class="clear_L"></div>
      </div>
      <div class="row errorPlace">
         <div class="r1">&nbsp;</div>
         <div class="errorMsg" id="c_fees_other_error"></div>
         <div class="clear_L"></div>
      </div>
   </div>
</div>
<div class="lineSpace_13">&nbsp;</div>

<div class="row">	
   <div>
      <div class="r1 bld">Course Eligibility:&nbsp;</div>
      <div class="r2">
         <?php
            if(isset($eligibilityArr))
            {
               if(count($eligibilityArr) >= 1)
               {
                  foreach($eligibilityArr as $criteriaNum)
                  {
                     $eligType[trim($criteriaNum['criteria'])]=trim($criteriaNum['value']);
                  }
               }
            }
         ?>

         <div class="lefttd">Min Qual</div>
         <div class="righttd">
            <select id="eligibility1" name="eligibility1">
               <?php 
                  $minqualTypes = array('Select'=>"",'Class X'=>'X','Class XII'=>'XII','Graduation'=>'Graduate','PostGraduation'=>'Post-Graduate');
                  if(isset($eligType['minqual']) && $eligType['minqual'] != "")
                  {
                     foreach($minqualTypes as $key => $val)
                     {
                        if($eligType['minqual'] == $val)
                        {
                           echo '<option value="'.$val.'" selected>'.$key.'</option>';
                        }else{
                           echo '<option value="'.$val.'">'.$key.'</option>';
                        }
                     }
                  }else{
                     foreach($minqualTypes as $key => $val)
                     {
                        echo '<option value="'.$val.'">'.$key.'</option>';
                     }
                  }
               ?>
            </select>
         </div>
         <div class="clear_L lineSpace_10">&nbsp;</div>
         <div class="lefttd">Marks</div>
         <div class="righttd">
            <input type="text" id="c_elig_marks" name="c_elig_marks" style="width:50px" value="<?php
               if(isset($eligType['marks']) && $eligType['marks'] != "")
               {
                  echo $eligType['marks'];
               }
               else
               {
                  echo '';
               }
            ?>" />
         </div>
         <div class="clear_L lineSpace_10">&nbsp;</div>
         <div class="lefttd">Min Exp (in yrs)</div>
         <div class="righttd">
            <select id="eligibility3" name="eligibility3">
               <?php 
                  $minExpTypes = array('Select'=>"",'1'=>'1 year','2'=>'2 years','3'=>'3 years','4'=>'4 years','5'=>'5 years');
                  if(isset($eligType['minexp']) && $eligType['minexp'] != "")
                  {
                     foreach($minExpTypes as $key => $val)
                     {
                        if($eligType['minexp'] == $val)
                        {
                           echo '<option value="'.$val.'" selected>'.$key.'</option>';
                        }else{
                           echo '<option value="'.$val.'">'.$key.'</option>';
                        }
                     }
                  }else{
                     foreach($minExpTypes as $key => $val)
                     {
                        echo '<option value="'.$val.'">'.$key.'</option>';
                     }
                  }
               ?>
            </select>
         </div>
         <div class="clear_L lineSpace_10">&nbsp;</div>
         <div class="lefttd">Max Exp (in yrs)</div>
         <div class="righttd">
            <select id="eligibility4" name="eligibility4">
               <?php 
                  $maxExpTypes = array('Select'=>"",'1'=>'1 year','2'=>'2 years','3'=>'3 years','4'=>'4 years','5'=>'5 years');
                  if(isset($eligType['maxexp']) && $eligType['maxexp'] != "")
                  {
                     foreach($maxExpTypes as $key => $val)
                     {
                        if($eligType['maxexp'] == $val)
                        {
                           echo '<option value="'.$val.'" selected>'.$key.'</option>';
                        }else{
                           echo '<option value="'.$val.'">'.$key.'</option>';
                        }
                     }
                  }else{
                     foreach($maxExpTypes as $key => $val)
                     {
                        echo '<option value="'.$val.'">'.$key.'</option>';
                     }
                  }
               ?>
            </select>
         </div>						
         <div class="clear_L lineSpace_10">&nbsp;</div>
         <div class="lefttd">
            <?php
               if(isset($eligType['others']))
               {
                  echo '<input type="checkbox" checked="checked" value="chk_elig_others" name="chk_elig_others" value="yes" onclick="showHideElement(\'other_elig\');"></span>Others
            </div>
            <div>
               <textarea name="other_elig" id="other_elig" maxlength="1000" validate="validateStr" minlength="5" style="" class="" caption="Eligibility" >'.$eligType['others'].'</textarea>
            </div>';
         }
         else
         {
            echo '<input type="checkbox" value="chk_elig_others" name="chk_elig_others" value="yes" onclick="showHideElement(\'other_elig\');"></span>Others
      </div>
      <div>
         <textarea name="other_elig" id="other_elig" maxlength="1000" validate="validateStr" minlength="5" style="display:none;" class="" caption="Eligibility" ></textarea>
      </div>';
   }
?>
<div class="clear_L row errorPlace">
   <div class="errorMsg" id="other_elig_error"></div>
</div>			
         </div>
      </div>
      <div class="clear_L"></div>
   </div>
   <div class="lineSpace_13">&nbsp;</div>

   <div class="row">
      <div>
         <div>
            <div class="r1 bld">Course Syllabus/Contents:&nbsp;</div>
            <div class="r2">
               <!-- <textarea type="text" name="c_contents" id="c_contents" validate="validateStr" maxlength="5000" minlength="0" class="w62_per mceEditor" style="height:130px" /><?php echo $contents ?></textarea> -->
               <textarea type="text" name="c_contents" id="c_contents" maxlength="5000" minlength="0" class="w62_per mceEditor" style="height:130px" validate="validateStr"/><?php echo $contents ?></textarea>
            </div>
            <div class="clear_L"></div>
         </div>
         <div class="row errorPlace">
            <div class="r1">&nbsp;</div>
            <div class="r2 errorMsg" id="c_contents_error"></div>
            <div class="clear_L"></div>
         </div>
      </div>
   </div>
   <div class="lineSpace_13">&nbsp;</div>

   <div class="row" style="display:none">
      <div>
         <div>
            <div class="r1 bld">Tests Required:&nbsp;</div>
            <div class="r2">
               <?php
                  $testreqTypes = array('TOEFL'=>"TOEFL",'IELTS'=>'IELTS','GRE'=>'GRE','CAT'=>'CAT','MAT'=>'MAT','SAT'=>'SAT');
                  if(isset($testsArr))
                  {
                     foreach($testreqTypes as $key => $val)
                     {
                        $found=0;
                        foreach($testsArr as $testType)
                        {
                           if($testType['test'] == $key)
                           {
                              echo '<input type="checkbox" value="'.$key.'" checked="checked" name="c_test[]" id="c_test[]">'.$key.'</br>';
                              $found =1;
                              break;
                           }
                        }
                        if($found==0){
                           echo '<input type="checkbox" value="'.$key.'" name="c_test[]" id="c_test[]">'.$key.'</br>';
                        }
                     }
                  }else{
                     foreach($testreqTypes as $key => $val)
                     {
                        echo '<input type="checkbox" value="'.$key.'" name="c_test[]" id="c_test[]">'.$key.'</br>';
                     }
                  }
               ?>

               <!--
               <div class="row">
                  <div class="float_L" style="width:80px"><span style="position:relative; top:2px"><input type="checkbox" value="TOEFL" name="c_test[]" id="c_test[]"></span>TOEFL</div>
                  <div class="float_L"><span style="position:relative; top:2px"><input type="checkbox" value="IELTS" name="c_test[]" id="c_test[]"></span>IELTS</div>
                  <div class="clear_L"></div>
               </div>
               <div class="row">
                  <div class="float_L" style="width:80px"><span style="position:relative; top:2px"><input type="checkbox" value="GRE" name="c_test[]" id="c_test[]"></span>GRE</div>
                  <div class="float_L"><span style="position:relative; top:2px"><input type="checkbox" value="CAT" name="c_test[]" id="c_test[]"></span>CAT</div>
                  <div class="clear_L"></div>
               </div>
               <div class="row">			
                  <div class="float_L" style="width:80px"><span style="position:relative; top:2px"><input type="checkbox" value="MAT" name="c_test[]" id="c_test[]"></span>MAT</div>
                  <div class="float_L"><span style="position:relative; top:2px"><input type="checkbox" value="SAT" name="c_test[]" id="c_test[]"></span>SAT</div>
                  <div class="clear_L"></div>
               </div>
               <div class="">
                  <span style="position:relative; top:2px"><input type="checkbox" value="Others" name="c_test[]" id="c_test[]" onclick="showHideElement('other_test');"></span>Others
                  -->
                  <?php
                     $foundOther =0;
                     $testArrOtherIndex=0;
                        foreach($testsArr as $testType)
                        {
                           if($testType['test'] == "Others")
                           {
                              $foundOther =1;
                              break;
                           }
                           $testArrOtherIndex++;
                        }
                        if($foundOther==1){
                           echo '<input type="checkbox" value="Others" checked="checked" name="c_test[]" id="c_test[]" onclick="showHideElement(\'other_test\');">Others</br>
                  <div style="position:relative; top:2px">
                              <textarea name="other_test" id="other_test" validate="validateStr" validate="validateStr" maxlength="250" minlength="5" style="" class="w62_per" caption="Other Tests" >'.$testsArr[$testArrOtherIndex]["value"].'</textarea></div></div>';
            }else{
               echo '<input type="checkbox" value="Others" name="c_test[]" id="c_test[]" onclick="showHideElement(\'other_test\');">Others</br>
                  <div style="position:relative; top:2px">
                     <textarea name="other_test" id="other_test" validate="validateStr" validate="validateStr" maxlength="250" minlength="5" style="display:none;" class="w62_per" caption="Other Tests" ></textarea></div></div>';
      }

                     ?>
            </div>
            <div class="clear_L"></div>
         </div>
         <div class="row errorPlace">
            <div class="r1">&nbsp;</div>
            <div class="r2 errorMsg" id="other_test_error"></div>
            <div class="clear_L"></div>
         </div>
      </div>
   </div>
   <div class="lineSpace_13">&nbsp;</div>

   <div class="row">
      <div>
         <div>
            <div class="r1 bld">Selection Criterion:&nbsp;</div>
            <div class="r2">
               <!-- <textarea type="text" name="c_selection_criteria" id="c_selection_criteria" validate="validateStr" maxlength="5000" minlength="0" class="w62_per mceEditor" style="height:130px;" /><?php echo $selection_criteria; ?></textarea> -->
               <textarea type="text" name="c_selection_criteria" id="c_selection_criteria" maxlength="5000" minlength="0" class="w62_per mceEditor" style="height:130px;" validate="validateStr" /><?php echo $selection_criteria; ?></textarea>
            </div>
            <div class="clear_L"></div>
         </div>
         <div class="row errorPlace">
            <div class="r1">&nbsp;</div>
            <div class="r2 errorMsg" id="c_selection_criteria_error" ></div>
            <div class="clear_L"></div>
         </div>
      </div>
   </div>
   <div class="lineSpace_13">&nbsp;</div>
   <?php
       $examSelectPanelAttribs = array('examSelectComboName'=>'c_test', 'examSelectComboCaption'=>'Tests Required', 'examSelected'=>$tests_required, 'otherExam'=> ($tests_required_other != '[]' ? $tests_required_other :''));
       $this->load->view('common/examSelectPanel', $examSelectPanelAttribs);
   ?>

   <div class="lineSpace_20">&nbsp;</div>

   <div class="row">
      <div class="mar_left_5p"><span class="normaltxt_11p_blk fontSize_13p OrgangeFont bld">Other Services</span></div>
      <div class="grayLine mar_top_5p"></div>							
   </div>
   <div class="lineSpace_10">&nbsp;</div>

   <div class="row">
      <div>
         <div>
            <div class="r1 bld">Placement Services:&nbsp;</div>
            <div class="r2">					
               <?php
                  if(!((strcasecmp($placements,"No")==0) || (strcasecmp($placements,"N.A.")==0)))
                  {
                     echo '  <span style="position:relative; top:2px"><input type="radio" name="c_placements" id="c_placements" checked="checked" value="yes" onClick="document.getElementById(\'c_placements_desc\').style.display=\'inline\';"/></span> Yes											
                     <span style="position:relative; top:2px"><input type="radio" name="c_placements" id="c_placements" value="no" onClick="document.getElementById(\'c_placements_desc\').style.display=\'none\';"/></span> No
                     <span style="position:relative; top:2px"><input type="radio" name="c_placements" id="c_placements" value="N.A." onClick="document.getElementById(\'c_placements_desc\').style.display=\'none\';"/></span> Info not available ';
                  }
                  else if(!strcasecmp($placements,"No"))
                  {
                     echo '  <span style="position:relative; top:2px"><input type="radio" name="c_placements" id="c_placements" value="yes" onClick="document.getElementById(\'c_placements_desc\').style.display=\'inline\';"/></span> Yes																	
                     <span style="position:relative; top:2px"><input type="radio" name="c_placements" id="c_placements" checked="checked" value="no" onClick="document.getElementById(\'c_placements_desc\').style.display=\'none\';"/></span> No
                     <span style="position:relative; top:2px"><input type="radio" name="c_placements" id="c_placements" value="N.A." onClick="document.getElementById(\'c_placements_desc\').style.display=\'none\';"/></span> Info not available ';
                  }
                  else if(!strcasecmp($placements,"N.A."))
                  {
                     echo '  <span style="position:relative; top:2px"><input type="radio" name="c_placements" id="c_placements" value="yes" onClick="document.getElementById(\'c_placements_desc\').style.display=\'inline\';"/></span> Yes																	
                     <span style="position:relative; top:2px"><input type="radio" name="c_placements" id="c_placements" value="no" onClick="document.getElementById(\'c_placements_desc\').style.display=\'none\';"/></span> No
                     <span style="position:relative; top:2px"><input type="radio" name="c_placements" id="c_placements" checked="checked" value="N.A." onClick="document.getElementById(\'c_placements_desc\').style.display=\'none\';"/></span> Info not available ';
                  }
               ?>

               <div class="lineSpace_5">&nbsp;</div>
               <?php
                  if(!((strcasecmp($placements,"No")==0) || (strcasecmp($placements,"N.A.")==0)))
                  {
                     echo '<textarea id="c_placements_desc" name="c_placements_desc" validate="validateStr" maxlength="1000" minlength="0" style="" class="w62_per" >'.$placements.'</textarea>';
                  }
                  else
                  {
                     echo '<textarea id="c_placements_desc" name="c_placements_desc" validate="validateStr" maxlength="1000" minlength="0" style="display:none;" class="w62_per" ></textarea>';
                  }
               ?>
            </div>
            <div class="clear_L"></div>
         </div>
         <div class="row errorPlace">
            <div class="r1">&nbsp;</div>
            <div id="c_placements_desc_error" class="r2 errorMsg"></div>
            <div class="clear_L"></div>
         </div>			
      </div>			
   </div>
   <div class="lineSpace_13">&nbsp;</div>

   <div class="row">
      <div>
         <div>
            <div class="r1 bld">Scholarships/ Financial Aid available:&nbsp;</div>
            <div class="r2">									
               <?php 
                  if(!((strcasecmp($scholarshipText,"No")==0) || (strcasecmp($scholarshipText,"N.A.")==0)))
                  {
                     echo '<span style="position:relative; top:2px"><input type="radio" name="c_scholarships" id="c_scholarships" value="yes" onClick="document.getElementById(\'c_schol_exams\').style.display=\'inline\';" checked="checked"/></span> Yes
                     <span style="position:relative; top:2px"><input type="radio" name="c_scholarships" id="c_scholarships" value="no" onClick="document.getElementById(\'c_schol_exams\').style.display=\'none\';"/></span> No
                     <span style="position:relative; top:2px"><input type="radio" name="c_scholarships" id="c_scholarships" value="N.A." onClick="document.getElementById(\'c_schol_exams\').style.display=\'none\';"/></span> Info not available ';
                  }
                  else if(!strcasecmp($scholarshipText,"No"))
                  {
                     echo '<span style="position:relative; top:2px"><input type="radio" name="c_scholarships" id="c_scholarships" value="yes" onClick="document.getElementById(\'c_schol_exams\').style.display=\'inline\';"/></span> Yes
                     <span style="position:relative; top:2px"><input type="radio" name="c_scholarships" id="c_scholarships" value="no" onClick="document.getElementById(\'c_schol_exams\').style.display=\'none\';" checked="checked"/></span> No
                     <span style="position:relative; top:2px"><input type="radio" name="c_scholarships" id="c_scholarships" value="N.A." onClick="document.getElementById(\'c_schol_exams\').style.display=\'none\';"/></span> Info not available ';
                  }
                  else if(!strcasecmp($scholarshipText,"N.A."))
                  {
                     echo '<span style="position:relative; top:2px"><input type="radio" name="c_scholarships" id="c_scholarships" value="yes" onClick="document.getElementById(\'c_schol_exams\').style.display=\'inline\';"/></span> Yes
                     <span style="position:relative; top:2px"><input type="radio" name="c_scholarships" id="c_scholarships" value="no" onClick="document.getElementById(\'c_schol_exams\').style.display=\'none\';" /></span> No
                     <span style="position:relative; top:2px"><input type="radio" name="c_scholarships" id="c_scholarships" value="N.A." onClick="document.getElementById(\'c_schol_exams\').style.display=\'none\';" checked="checked"/></span> Info not available ';
                  }
               ?>
               <div class="lineSpace_5">&nbsp;</div>
               <?php
                  if(!((strcasecmp($scholarshipText,"No")==0) || (strcasecmp($scholarshipText,"N.A.")==0))){
                     echo '<textarea type="text" name="c_schol_exams" id="c_schol_exams" validate="validateStr" maxlength="5000" minlength="0" style="" class="w62_per" />'.$scholarshipText.'</textarea>';
                  }else{
                     echo '<textarea type="text" name="c_schol_exams" id="c_schol_exams" validate="validateStr" maxlength="5000" minlength="0" style="display:none;" class="w62_per" /></textarea>';
                  }
               ?>
            </div>
            <div class="clear_L"></div>
         </div>
         <div class="row errorPlace">
            <div class="r1">&nbsp;</div>
            <div id="c_schol_exams_error" class="r2 errorMsg"></div>
            <div class="clear_L"></div>
         </div>
      </div>
   </div>
   <div class="lineSpace_13">&nbsp;</div>

   <div class="row">	
      <div>
         <div class="r1 bld">Course Hostel Facility:&nbsp;</div>
         <div class="r2">
            <?php
               if(!((strcasecmp($hostel,"No")==0) || (strcasecmp($hostel,"N.A.")==0)))
               {
                  echo '<span style="position:relative; top:2px"><input type="radio" name="c_hostel_facility" id="c_hostel_facility" checked="checked" value="Yes"/></span> Yes
                  <span style="position:relative; top:2px"><input type="radio" name="c_hostel_facility" id="c_hostel_facility" value="No" /></span> No
                  <span style="position:relative; top:2px"><input type="radio" name="c_hostel_facility" id="c_hostel_facility" value="N.A." /></span> Info not available ';
               }
               else if(!strcasecmp($hostel,"No"))
               {
                  echo '<span style="position:relative; top:2px"><input type="radio" name="c_hostel_facility" id="c_hostel_facility" value="Yes"/></span> Yes
                  <span style="position:relative; top:2px"><input type="radio" name="c_hostel_facility" id="c_hostel_facility" checked="checked" value="No" /></span> No
                  <span style="position:relative; top:2px"><input type="radio" name="c_hostel_facility" id="c_hostel_facility" value="N.A." /></span> Info not available ';
               }
               else if(!strcasecmp($hostel,"N.A."))
               {
                  echo '<span style="position:relative; top:2px"><input type="radio" name="c_hostel_facility" id="c_hostel_facility" value="Yes"/></span> Yes
                  <span style="position:relative; top:2px"><input type="radio" name="c_hostel_facility" id="c_hostel_facility" value="No" /></span> No
                  <span style="position:relative; top:2px"><input type="radio" name="c_hostel_facility" id="c_hostel_facility" checked="checked" value="N.A." /></span> Info not available ';
               }
            ?>
         </div>
         <div class="clear_L"></div>
      </div>	
   </div>
   <div class="lineSpace_13">&nbsp;</div>
 
   <?php if(false){ ?>
   <div id="admin_details_main">
      <div id="admin_details" style="<?php echo $style;?>">			
         <div class="row">
             <div class="mar_left_5p"><span class="normaltxt_11p_blk fontSize_13p OrgangeFont bld">Institute Contact Details (Update the institute contact details to receive emails and phone enquiries from the interested candidates.)</span></div>
            <div class="grayLine mar_top_5p"></div>							
         </div>
         <div class="lineSpace_13">&nbsp;</div>

         <div class="row">
            <div>
               <div>
                  <div class="r1 bld">Name:&nbsp;</div>
                  <div class="r2">
                     <input type="text" name="c_cordinator_name" id="c_cordinator_name"  validate="validateStr" maxlength="100" minlength="0" style="width:200px" caption="Name" value="<?php echo $contact_name;?>"/>
                  </div>
                  <div class="clear_L"></div>
               </div>
               <div class="row errorPlace">
                  <div class="r1">&nbsp;</div>
                  <div class="r2 errorMsg" id="c_cordinator_name_error"></div>
                  <div class="clear_L"></div>
               </div>
            </div>
         </div>
         <div class="lineSpace_13">&nbsp;</div>

         <div class="row">	
            <div>
               <div>
                  <div class="r1 bld">Contact No:&nbsp;</div>
                  <div class="r2">
                     <input type="text" name="c_cordinator_no" id="c_cordinator_no"  maxlength="100" minlength="5" style="width:200px" caption="Contact Number" value="<?php echo $contact_cell;?>"/>
                  </div>
                  <div class="clear_L"></div>
               </div>
               <div class="row errorPlace">
                  <div class="r1">&nbsp;</div>
                  <div class="r2 errorMsg" id="c_cordinator_no_error"></div>
                  <div class="clear_L"></div>
               </div>
            </div>
         </div>
         <div class="lineSpace_13">&nbsp;</div>

         <div class="row">
            <div>
               <div>
                  <div class="r1 bld">Email:&nbsp;</div>
                  <div class="r2">
                     <input type="text" name="c_cordinator_email" id="c_cordinator_email" validate="validateEmail" maxlength="125" minlength="0" style="width:200px" caption="Email" value="<?php echo $contact_email;?>"/>
                  </div>
                  <div class="clear_L"></div>
               </div>
               <div class="row errorPlace">
                  <div class="r1">&nbsp;</div>
                  <div class="r2 errorMsg" id="c_cordinator_email_error" ></div>
                  <div class="clear_L"></div>
               </div>
            </div>		
         </div>
      <div class="lineSpace_13">&nbsp;</div>

      <div class="row">
	 <div>
	    <div>
	       <div class="r1 bld">Website Address:&nbsp;</div>
	       <div class="r2">
                   <input type="text" name="c_website" id="c_website" maxlength="1000" minlength="0" style="width:200px" caption="College Website Address" validate="validateUrl" value="<?php echo $url; ?>"/>
	       </div>
	       <div class="clear_L"></div>
	    </div>
	    <div class="row errorPlace">
	       <div class="r1">&nbsp;</div>
	       <div class="r2 errorMsg" id="c_website_error" ></div>
	       <div class="clear_L"></div>
	    </div>
	 </div>
      </div>
      <div class="lineSpace_20">&nbsp;</div>

      </div>
   </div>
   <?php } ?>
   <input type='hidden' name='c_media_content' value='1'/>
   <?php    $this->load->view('enterprise/cmsMediaContent'); ?>


<div class="lineSpace_13">&nbsp;</div>
<?php if ($usergroup != "cms"): ?>
<div class="row">
	 <div>
	    <div>
	       <div class="r1 bld">Type the characters you see in picture:<span class="redcolor">*</span></div>
	       <div class="r2">
		  			<img src="/CaptchaControl/showCaptcha?width=100&height=40&characters=5&secvariable=seccode&randomkey=<?php echo rand(); ?>" id="topicCaptcha"/><br />
		  			<input type="text" name="captcha_text" id="captcha_text" caption="Security Code" tip="secCode" >
	       </div>
	       <div class="clear_L"></div>
	    </div>
	    <div class="row errorPlace">
	       <div class="r1">&nbsp;</div>
	       <div class="r2 errorMsg" id="captcha_text_error"></div>
	       <div class="clear_L"></div>
	    </div>
	 </div>
</div>
<div class="lineSpace_13">&nbsp;</div>
<?php endif; ?>


   <input type="hidden" name="addCourse" value="1" />
   <div class="lineSpace_15">&nbsp;</div>
   <div style="display: inline; float:left; width:100%">
      <div class="buttr3">
         <button class="btn-submit7 w9" value="addCourse" type="button" onClick="validateCourseListing(this.form);">
            <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Update Course</p></div>
         </button>
      </div>
<?php $redirectLocation = "/";
	if ( isset($_SERVER['HTTP_REFERER']) && ($_SERVER['HTTP_REFERER']!="") )
		$redirectLocation = $_SERVER['HTTP_REFERER'];
?>
      <div class="buttr2">
      <button class="btn-submit11 w4" value="cancel" type="button" onClick="location.replace('<?php echo $redirectLocation;?>');" >
         <!-- <button class="btn-submit11 w4" value="cancel" type="button" onClick="resetForm();" > -->
            <div class="btn-submit11"><p class="btn-submit12">Cancel</p></div>
         </button>
      </div>
      <div class="clear_L"></div>
   </div>
   <div class="clear_L"></div>
</form>
<!--End_Course_Listing_Form-->
<script>
   //getCategorySelectBox();
   getCategories();
   selectMultiComboBox(document.getElementById('c_categories'),selectCatArr);
   //document.courseListing.reset();
   addOnBlurValidate(document.getElementById('courseListing'));
   addOnFocusToopTip(document.getElementById('courseListing'));
   //getCitiesForCountryListAnother(1);
   var formName = "courseListing";

   function resetForm()
   {
         document.courseListing.reset();
         //hideelement('other_test','c_placements_desc','c_schol_exams','c_fees_other','other_elig','existing_college');
         hideelement('other_test','c_placements_desc','c_schol_exams','c_fees_other','other_elig');
         addCollegeJS();
   }

   function validateCourseListing(objForm)
   {
         var duration_flag = checkCourseDuration();
         var catCombo_flag = validateCatCombo('c_categories',10,1);
         var flag = validateFields(objForm);
        // var f1 = validateExistingCollege();
         var f2 = validateCourseType();
        // if(flag==true && duration_flag==true && catCombo_flag==true && f1==true && f2==true)
        if(flag==true && duration_flag==true && catCombo_flag==true && f2==true)
         <?php if ($usergroup!="cms") : ?>
         validateCaptchaForListing();
         <?php  else : ?>
         objForm.submit();
         <?php endif; ?>

   }

   function fillStartDateFirst(){
           if(document.getElementById('course_start_date').value == ""){
                   document.getElementById('course_end_date_error').innerHTML = "Please fill Course Start date first!";
                   document.getElementById('course_end_date_error').parentNode.style.display = 'inline';
                   return false;
               }else{
                   document.getElementById('course_end_date_error').innerHTML = "";
                   document.getElementById('course_end_date_error').parentNode.style.display = 'none';
                   return true;
           }
   }

   function checkCourseDuration()
   {
         if (document.getElementById('c_duration1').value == "")
         {
               document.getElementById('c_duration1_error').innerHTML = "Please select the course duration";
               //document.getElementById('c_duration1_error').style.display = "inline";
               document.getElementById('c_duration1_error').parentNode.style.display = 'inline';
               return false;
         }
         else
         {
               document.getElementById('c_duration1_error').innerHTML = "";
               //document.getElementById('c_duration1_error').style.display = "none";
               document.getElementById('c_duration1_error').parentNode.style.display = 'none';
               return true;
         }
   }
   tinyMCE.init({ mode : "textareas", theme : "simple",editor_selector : "mceEditor", editor_deselector : "mceNoEditor"});

   <?php if($packType > 0){ ?>
   editPackSpecificChanges("<?php echo $listingType; ?>");
   <?php  }else{ ?>
   editPackSpecificChangesCMS("<?php echo $listingType; ?>");
   <?php  } ?>

   fillProfaneWordsBag();
</script>
