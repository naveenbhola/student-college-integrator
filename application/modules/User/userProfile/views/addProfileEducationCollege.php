<style type="text/css">
  .suggestion-box{position: absolute;background: #fff;z-index: 99;border:1px solid #ccc;width:277px;border-width: 0px 1px 1px 1px; box-shadow: none; padding: 0px;}
  .suggestion-box  li{padding: 10px 16px 10px; border-bottom: 1px solid #F7F7F7;}
  .suggestion-box .suggestion-box-active-option {background: #F9F9F9 none repeat scroll 0% 0%;color: #000;list-style: outside none none;}
  .suggestion-box li span {display: block;color: #999;font-size: 12px;font-weight: 400;line-height: 20px;}
  .suggestion-box  li .suggestion-box-normal-option {background: #FFF none repeat scroll 0% 0%;}
</style>

<?php 
if($CourseCompletionDate){
  $date =  $CourseCompletionDate->format('Y-m-d H:i:s');
  $completionDate = substr($date, 0, 4);
}
$courseLevel =  array('-1'=>'Select','10' =>'X','12' => 'XII','UG'=>'UG','PG'=>'PG','PHD'=>'PHD');

$years = range(date('Y',strtotime('+3 years')),1990);
        $courseCompletionYear = array_combine($years,$years);

$board = array('CBSE'=>'CBSE','ICSE'=>'ICSE/State Boards','IGCSE' => 'Cambridge IGCSE');

$nameInstitute = array('xth'=>'xthSchool','xiith'=>'xiithSchool','UG'=>'bachelorsCollege','PG'=>'mastersCollege','PHD'=>'phdCollege');
$nameSubject= array('xth'=>'CurrentSubjects[]','xiith'=>'xiiSpecialization','UG'=>'bachelorsStream','PG'=>'mastersStream','PHD'=>'phdSpec');
$nameCourseCompletionDate = array('xth'=>'xthCompletionYear','xiith'=>'xiiYear','UG'=>'graduationCompletionYear','PG'=>'mastersCompletionYear','PHD'=>'phdCompletionYear');
$nameBoard = array('xth'=>'tenthBoard','xiith'=>'xiiBoard');
$nameMarks = array('xth'=>'tenthmarks','xiith'=>'xiiMarks','UG'=>'bachelorsMarks','PG'=>'mastersMarks','PHD'=>'phdMarks');
$nameUniversity = array('UG'=>'bachelorsUniv','PG' =>'mastersUniv', 'PHD'=>'phdUniv');
$nameCollege = array('UG'=>'bachelorsCollege','PG' =>'mastersCollege', 'PHD'=>'phdCollege');
$nameDegree = array('UG'=>'bachelorsDegree','PG' =>'mastersDegree', 'PHD'=>'phdDegree');
$nameSpecialization = array('UG'=>'bachelorsSpec','PG' =>'mastersSpec', 'PHD'=>'phdStream');
//$nameStream = array('UG'=>'bachelorsStream','PG' =>'mastersStream', 'PHD'=>'phdSpec');
$nameStream = array('UG'=>'bachelorsStream','PG' =>'mastersStream', 'PHD'=>'phdStream');  //work around, correct mapping is above one
$icseMarks = array(''=>'Select Marks','50'=>'< than 50%','60'=>'50% to 60%','70' => '60% to 70%','80'=>'70% to 80%','90'=>'80% to 90%','100' => '90% or above');
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

                                      
                                      
                                    </li>

                                      <li class="p-l">
                                         <span class="p-s">
                                           <label>University Name</label>
                                           <input maxlength =150 type="text" name="<?php echo $nameUniversity[$level]?>" value="<?php echo  $Board;?>" class="prf-inpt"/>
                                         </span>

                                         <span class="p-s">
                                           <label> College Name<i>*</i></label>
                                           <input id="<?php echo $nameInstitute[$level].'_'.$regFormId;?>" maxlength =150 mandatory="1" profanity="1" label="College Name"  default="College Name" caption="your College Name" regFieldId ="<?php echo $nameInstitute[$level]?>" onblur="shikshaUserProfileForm['<?php echo $regFormId; ?>'].validateField(this); hideSuggestionBox('<?php echo $nameInstitute[$level].'_'.$regFormId;?>','<?php echo $level; ?>');" type="text" onclick="showSuggestions('<?php echo $nameInstitute[$level].'_'.$regFormId;?>','<?php echo $level; ?>');" name="<?php echo $nameCollege[$level]?>" value="<?php echo  $InstituteName;?>" class="prf-inpt" minlength="1" autocomplete="off"/>
                                           <ul class="prf-sugstr" style="display: none;" id="suggestions_container_<?php echo $level; ?>"></ul>

                                           <span > <div class="regErrorMsg" id="<?php echo $nameInstitute[$level].'_error_'.$regFormId; ?>"></div> </span >
                                         </span>

                                         

                                      </li>

                                      <li class="p-l">
                                         <span class="p-s">
                                           <label>Degree/Diploma Name<i>*</i></label>
                                           <input id="<?php echo $nameDegree[$level].'_'.$regFormId;?>" maxlength =150 mandatory="1" profanity="1" label="Degree/Diploma Name"  default="Degree/Diploma Name" caption="your Degree/Diploma Name" regFieldId ="<?php echo $nameDegree[$level]?>" onblur="shikshaUserProfileForm['<?php echo $regFormId; ?>'].validateField(this);" type="text"  name="<?php echo $nameDegree[$level]?>" value="<?php echo  $Name;?>" class="prf-inpt"/>

                                            <span > <div class="regErrorMsg" id="<?php echo $nameDegree[$level].'_error_'.$regFormId; ?>"></div> </span >
                                         </span>

                                         <span class="p-s">
                                           <label>Specialization </label>
                                           <input maxlength =150 type="text" name="<?php echo $nameStream[$level]?>" value="<?php echo  $Subjects;?>" class="prf-inpt"/>
                                         </span>

                                      </li>

                                    <li class="p-l">

                                      <span class="p-s">
                                         <label>Course Completion Year </label>
                                         <select class="dfl-drp-dwn" name="<?php echo $nameCourseCompletionDate[$level]?>" >
                                                  <option value =""> Select Year </option>
                                                  <?php 
                                                    foreach($courseCompletionYear as $value=>$description){ ?>
                                                          <option <?php  if ($value == $completionDate) {echo "selected";} ?>  value="<?php echo $value; ?>" > <?php echo $description; ?> </option>
                                                   <?php } ?>
                                          </select>
                                      </span>

                                   
                                      <?php if ($level != 'PHD'){?>
                                       <span class="p-s">
                                         <label>Marks</label>
                                         <div class="custom-drp">
                                           
                                          <select  id="marks_percnt_<?php echo $level?>"  class="dfl-drp-dwn" name="<?php echo $nameMarks[$level]?>" >
                                            <?php foreach ($icseMarks as $value => $desc) {?>
                                               <option <?php  if ($value == $Marks) {echo "selected='true'";} ?>  value="<?php echo $value;?>"><?php echo $desc;?></option>
                                             <?php }?>
                                          </select>

                                         </div>
                                      </span>
                                      <?php }?>
                                    </li>
                                    <p class="clr"></p> 
                                 </ul>

                             