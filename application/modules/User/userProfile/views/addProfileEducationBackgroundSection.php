
<?php 

if($CourseCompletionDate){
  $date =  $CourseCompletionDate->format('Y-m-d H:i:s');
$completionDate = substr($date, 0, 4);
}


$courseLevel =  array('-1'=>'Select','10' =>'X','12' => 'XII','UG'=>'UG','PG'=>'PG','PHD'=>'PHD');

$years = range(date('Y',strtotime('+3 years')),1990);
        $courseCompletionYear = array_combine($years,$years);

$board = array('CBSE'=>'CBSE','ICSE'=>'ICSE/State Boards','IGCSE' => 'Cambridge IGCSE','IBMYP'=>'International Baccalaureate','NIOS'=>'NIOS');
$cbseMarks = array(''=>'Select Marks','4 - 4.9'=>'4 - 4.9', '5 - 5.9'=>'5 - 5.9', '6 - 6.9'=>'6 - 6.9', '7 - 7.9'=>'7 - 7.9', '8 - 8.9'=>'8 - 8.9', '9 - 10.0'=>'9 - 10.0');
$icseMarks = array(''=>'Select Marks','50'=>'< than 50%','60'=>'50% to 60%','70' => '60% to 70%','80'=>'70% to 80%','90'=>'80% to 90%','100' => '90% or above');

$IGCSEMarks = array(''=>'Select Marks','A*'=>'A*','A'=>'A','B' => 'B','C'=>'C','D'=>'D','E' => 'E','F'=>'F','G'=>'G');
$IBMYPMarks = array(''=>'Select Marks','28'=>'28','29'=>'29','30'=>'30','31'=>'31','32'=>'32','33'=>'33','34'=>'34','35'=>'35','36'=>'36','37'=>'37','38'=>'38','39'=>'39','40'=>'40','41'=>'41','42'=>'42','43'=>'43','44'=>'44','45'=>'45','46'=>'46','47'=>'47','48'=>'48','49'=>'49','50'=>'50','51'=>'51','52'=>'52','53'=>'53','54'=>'54','55'=>'55','56'=>'56');
$NIOSMarks  = array(''=>'Select Marks','< than 50%'=>'50','50% to 60%'=>'60','60% to 70%'=> '70','70% to 80%'=>'80','80% to 90%'=>'90','90% or above'=>'100');
$stream = array('Science' => 'Science','Commerce' => 'Commerce','Arts' => 'Arts');

                                           
$nameSpecialization = array('xiith'=>'xiiSpecialization','UG'=>'bachelorsSpec','PG'=>'mastersSpec', 'PHD'=>'phdStream');
$nameInstitute = array('xth'=>'xthSchool','xiith'=>'xiithSchool','UG'=>'bachelorsCollege','PG'=>'mastersCollege','PHD'=>'phdCollege');
$nameSubject= array('xth'=>'CurrentSubjects[]','xiith'=>'xiiSpecialization','UG'=>'bachelorsStream','PG'=>'mastersStream','PHD'=>'phdSpec');
$nameCourseCompletionDate = array('xth'=>'xthCompletionYear','xiith'=>'xiiYear','UG'=>'graduationCompletionYear','PG'=>'mastersCompletionYear','PHD'=>'phdCompletionYear');
$nameBoard = array('xth'=>'tenthBoard','xiith'=>'xiiBoard');
$nameMarks = array('xth'=>'tenthmarks','xiith'=>'xiiMarks','UG'=>'bachelorsMarks','PG'=>'mastersMarks','PHD'=>'phdMarks');

$subjects = array("Accountancy","Agriculture","Anthropology","Arts","Biology","Biotechnology","Business Studies/Management","Chemistry","Commerce","Computer Science","Design and Technology","Design and Textiles","Earth Sciences","Economics","Engineering Graphics","English","Entrepreneurship","Environmental Studies","Fashion Designing","Film","Fine Arts","Food Studies","Foreign Languages","French","General Studies","Geography","German","Health Sciences","Hindi","History, Civics & Geography","Home Science","Information and Communication Technology","Information Practices","Law","Marine science","Math studies","Mathematics","Multimedia/Technology","Other subjects","Philosophy","Physical Education","Physics","Political Science","Psychology","Science","Social Science","Sociology","Technical Drawing","Travel and Tourism");
asort($subjects);


?>

                                <ul id ="<?php echo $level ?>" class="p-ul">
                                  
                                  <div class="remove-lnk">
                                            <a onclick="removeEduSection('<?php echo $level;?>');" href="javascript:void(0);">Delete</a>
                                  </div>

                                    <li class="p-l">
                                      <span class="p-s">

                                         <label>Course Level<i>*</i></label>

                                        <select id="courseLevel_<?php echo $level;?>" class="dfl-drp-dwn" onchange="changeEducationForm('<?php echo $regFormId;?>',this.options[this.selectedIndex].text,this.id);" name="EducationBackground[]"  >
                                           
                                                  <?php 
                                                   
                                                    foreach($courseLevel as $value=>$description){ ?>
                                                          <option  <?php  if ($value == $level) {echo "selected='true'";} ?>  value="<?php echo $value; ?>" > <?php echo $description; ?> </option>
                                                   <?php } ?>
                                          </select>

                                          
                                      </span>

                                       <span class="p-s" id='searchBoxCampusConnect'>
                                         <label>School Name<i>*</i></label>
                                          <input id="<?php echo $nameInstitute[$levelKey].'_'.$regFormId;?>" maxlength =150 mandatory="1" profanity="1" label="School Name"  default="School Name" caption="your School Name" regFieldId ="<?php echo $nameInstitute[$levelKey]?>" onblur="shikshaUserProfileForm['<?php echo $regFormId; ?>'].validateField(this);" type="text" name="<?php echo $nameInstitute[$levelKey]?>" value="<?php echo  $InstituteName;?>" class="prf-inpt" />

                                          <span > <div class="regErrorMsg" id="<?php echo $nameInstitute[$levelKey].'_error_'.$regFormId; ?>"></div> </span >
                                      </span>
                                      
                                    </li>

                                    <li class="p-l">
                                      <span class="p-s">
                                         <label>Course Completion Year</label>
                                         <select class="dfl-drp-dwn" name="<?php echo $nameCourseCompletionDate[$levelKey]?>" >
                                                  <option value =""> Select Year </option>
                                                  <?php 
                                                    foreach($courseCompletionYear as $value=>$description){ ?>
                                                          <option <?php  if ($value == $completionDate) {echo "selected='true'";} ?>  value="<?php echo $value; ?>" > <?php echo $description; ?> </option>
                                                   <?php } ?>
                                          </select>
                                      </span>

                                       <span class="p-s">
                                         <label>Board</label>
                                          <select onchange="changeMarks(this.value,'<?php echo $level;?>')" class="dfl-drp-dwn" name="<?php echo $nameBoard[$levelKey] ?>"  >
                                                  <option value =''> Select Board </option>
                                                  <?php 
                                                    
                                                    foreach($board as $value=>$description){ ?>
                                                          <option <?php  if ($value == $Board) {echo "selected='true'";} ?> value="<?php echo $value; ?>" > <?php echo $description; ?> </option>
                                                   <?php } ?>
                                          </select>
                                      </span>
                                    </li>


                                    <li  class="p-l">
                                      <span tabIndex=1 onblur="hideSubjectLayer();" class="p-s" style="display:none;">
                                         <label>Subjects</label>

                                              <div class="custom-drp">
                                              <p class="custm-srch"  onclick="displaySubjects();applyScrollBar('abc');" id="subjectDisplay" > <?php if(count($Subjects) > 0 && !empty($Subjects[0])){ echo limitTextLength(implode(', ', $Subjects), 30);}else{ echo 'Select Subjects'; }?> </p>              
                                             
                                      
                                           <div class="custm-drpdwn-dflt" id="subjectsDrop">
                                              <div class="scrollbar1" id="abc">
                                                  <div class="scrollbar" style="">
                                                    <div class="track" style="">
                                                      <div class="thumb" style="">
                                                        <div class="end"></div>
                                                      </div>
                                                    </div>
                                                  </div>

                                          <div class="viewport" style="height: 145px;">
                                            <div class="overview" style="top:0px;">

                                                   <ul class="checkul">

                                                    <?php foreach ($subjects as $sub) { ?>

                                                      <li>
                                                        <input <?php if(in_array($sub, $Subjects)){?>checked<?php }?> type="checkbox" value="<?php echo $sub;?>" name ="CurrentSubjects[]" class="nav-inputChk" data-val="<?php echo $sub;?>" id="<?php echo $sub;?>">
                                                        <label for="<?php echo $sub;?>" class="nav-chck1"> <i class="icons ic_checkdisable11" ></i><?php echo $sub; ?></label>  
                                                     </li>

                                                    <?php }?>

                                                   </ul>
                                                 </div>
                                                  <p class="clr"></p>
                                               </div>

                                        </div>
                                       
                                      </div>
                                     <p class="clr"></p>
                                   </div>
                                        


                                      </span>

                                      

                                       <span class="p-s">
                                         <label>Marks</label>
                                         <div class="custom-drp">
                                            <select id="marks_grade_<?php echo $level?>" <?php if ($Board != 'CBSE' && !empty($Board)) {?>style="display:none" <?php } else{?> name="<?php echo $nameMarks[$levelKey]?>" <?php }?> class="dfl-drp-dwn"  >
                                              
                                              <?php if($Marks === 0){ $Marks ='';}?>
                                             <?php foreach ($cbseMarks as $value => $desc) {?>
                                               <option <?php  if ($value == $Marks) {echo "selected";} ?> value="<?php echo $value;?>"><?php echo $desc;?></option>
                                             <?php }?>
                                             </select>

                                          <select id="marks_percnt_<?php echo $level?>" <?php if ($Board != 'ICSE' && $Board != 'NIOS'){?>style="display:none" <?php } else{?> name="<?php echo $nameMarks[$levelKey]?>" <?php }?> class="dfl-drp-dwn"  >
                                            <?php foreach ($icseMarks as $value => $desc) {?>
                                               <option <?php  if ($value == $Marks) {echo "selected";} ?>  value="<?php echo $value;?>"><?php echo $desc;?></option>
                                             <?php }?>
                                          </select>

                                          <select id="marks_cambridge_<?php echo $level?>" <?php if ($Board != 'IGCSE'){?>style="display:none" <?php } else{?> name="<?php echo $nameMarks[$levelKey]?>" <?php }?> class="dfl-drp-dwn">
                                           <?php foreach ($IGCSEMarks as $value => $desc) {?>
                                               <option <?php  if ($value == $Marks) {echo "selected";} ?> value="<?php echo $value;?>"><?php echo $desc;?></option>
                                             <?php }?>
                                          </select>

                                          <select id="marks_ibmyp_<?php echo $level?>" <?php if ($Board != 'IBMYP'){?>style="display:none" <?php } else{?> name="<?php echo $nameMarks[$levelKey]?>" <?php }?> class="dfl-drp-dwn">
                                           <?php foreach ($IBMYPMarks as $value => $desc) {?>
                                               <option <?php  if ($value == $Marks) {echo "selected";} ?> value="<?php echo $value;?>"><?php echo $desc;?></option>
                                             <?php }?>
                                          </select>   

                                         </div>

                                          
                                      </span>
                                    </li>

                                 <p class="clr"></p>
                                 </ul>

