<?php 

if($CourseCompletionDate){
  $date =  $CourseCompletionDate->format('Y-m-d H:i:s');
$completionDate = substr($date, 0, 4);
}


$courseLevel =  array('-1'=>'Select','10' =>'X','12' => 'XII','UG'=>'UG','PG'=>'PG','PHD'=>'PHD');

$years = range(date('Y',strtotime('+3 years')),1990);
        $courseCompletionYear = array_combine($years,$years);

$board = array('CBSE'=>'CBSE','ICSE'=>'ICSE/State Boards','IGCSE' => 'Cambridge IGCSE');
$cbseMarks = array(''=>'Select Marks','A1'=>'A1','A2'=>'A2','B1' => 'B1','B2'=>'B2','C1'=>'C1','C2' => 'C2','D'=>'D','E1'=>'E1','E2'=>'E2');
$icseMarks = array(''=>'Select Marks','50'=>'< than 50%','60'=>'50% to 60%','70' => '60% to 70%','80'=>'70% to 80%','90'=>'80% to 90%','100' => '90% or above');

$stream = array('Science' => 'Science','Commerce' => 'Commerce','Arts' => 'Arts');

                                           
$nameSpecialization = array('xiith'=>'xiiSpecialization','UG'=>'bachelorsSpec','PG'=>'mastersSpec', 'PHD'=>'phdStream');
$nameInstitute = array('xth'=>'xthSchool','xiith'=>'xiithSchool','UG'=>'bachelorsCollege','PG'=>'mastersCollege','PHD'=>'phdCollege');
$nameSubject= array('xth'=>'CurrentSubjects[]','xiith'=>'xiiSpecialization','UG'=>'bachelorsStream','PG'=>'mastersStream','PHD'=>'phdSpec');
$nameCourseCompletionDate = array('xth'=>'xthCompletionYear','xiith'=>'xiiYear','UG'=>'graduationCompletionYear','PG'=>'mastersCompletionYear','PHD'=>'phdCompletionYear');
$nameBoard = array('xth'=>'tenthBoard','xiith'=>'xiiBoard');
$nameMarks = array('xth'=>'tenthmarks','xiith'=>'xiiMarks','UG'=>'bachelorsMarks','PG'=>'mastersMarks','PHD'=>'phdMarks');


?>

                                <ul id ="<?php echo $level ?>" class="p-ul">
                                  
                                  <div class="remove-lnk">
                                            <a onclick="removeEduSection('<?php echo $level;?>');" href="javascript:void(0);">Delete</a>
                                  </div>

                                    <li class="p-l btm">
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
                                          <select class="dfl-drp-dwn" name="<?php echo $nameBoard[$levelKey] ?>"  >
                                                  <option value =''> Select Board </option>
                                                  <?php 
                                                    
                                                    foreach($board as $value=>$description){ ?>
                                                          <option <?php  if ($value == $Board) {echo "selected='true'";} ?> value="<?php echo $value; ?>" > <?php echo $description; ?> </option>
                                                   <?php } ?>
                                          </select>
                                      </span>
                                    </li>


                                    <li class="p-l">

                                      <span class="p-s">
                                         <label>Stream</label>
                                          <select class="dfl-drp-dwn" name="<?php echo $nameSpecialization[$levelKey] ?>"  >
                                                  <option value =''> Select Stream </option>
                                                  <?php 
                                                    
                                                    foreach($stream as $value=>$description){ ?>
                                                          <option <?php  if ($value == $Specialization) {echo "selected='true'";} ?> value="<?php echo $value; ?>" > <?php echo $description; ?> </option>
                                                   <?php } ?>
                                          </select>
                                      </span>

                                      

                                       <span class="p-s">
                                         <label>Marks </label>
                                         <div class="custom-drp">
                                             
                                          <select  id="marks_percnt_<?php echo $level?>"  class="dfl-drp-dwn" name="xiiMarks" >
                                            <?php foreach ($icseMarks as $value => $desc) {?>
                                               <option <?php  if ($value == $Marks) {echo "selected='true'";} ?>  value="<?php echo $value;?>"><?php echo $desc;?></option>
                                             <?php }?>
                                          </select> 

                                         </div>

                                          
                                      </span>
                                    </li>

                                 <p class="clr"></p>
                                 </ul>

                             