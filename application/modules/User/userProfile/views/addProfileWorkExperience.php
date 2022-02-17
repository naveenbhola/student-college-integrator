<?php 
$regFormId = random_string('alnum', 6);
?>
<section class="prf-box-grey" >
                          <div class="prft-titl">
                               <div class="caption">
                                  <p>WORK EXPERIENCE</p>
                               </div>
                          </div>

                       <!--profile-tab content-->
                        <div class="frm-bdy">
                           <form class="prf-form" id="registrationForm_<?=$regFormId?>">
                              <ul class="p-ul">
                                    <li class="p-l">
                                      <span class="p-s">
                                         <label>Total Work Experience</label> 

                                           <select class="dfl-drp-dwn" name="workExperience" id="totalWorkEx_<?php echo $regFormId; ?>" >
                                                  <?php 
                                               

                                                    foreach($WorkExRange as $value=>$description){ ?>
                                                          <option <?php  if ($value == $personalInfo['Experience'] ) { echo "selected='true'";} ?> value="<?php echo $value; ?>" > <?php echo $description; ?> </option>
                                                   <?php } ?>
                                          </select>

                                      </span>
                                    </li>
                                    <p class="clr"></p>
                                   </ul>

                        

                        <div id ="workExAction">           
                          <input id="workExpCount" type="hidden" value="1" name='workExpCount' />
                      
                        <?php $flag =true;
                          $count =0;
                          for ($i=1; $i <=10 ; $i++) { 
                              $workExLevel = 'workExp'.$i;
                              $workExLevelData = $$workExLevel;

                              if(!$flag){
                                continue;
                              }

                               $workExLevelData['fieldId'] = $i;
                               $workExLevelData['regFormId'] = $regFormId;

                              if(!empty($workExLevelData) && !empty($workExLevelData['Employer'])){ 
                                $count++;
                                $this->load->view('userProfile/addWorkExperienceSection',$workExLevelData);
                              }

                               if(empty($workExp1)){
                               $count++;
                                $this->load->view('userProfile/addWorkExperienceSection',$workExLevelData);
                                $flag =false;
                              }
                          }                          
                        ?>  
                      </div>
                          
                              <div class="lft-sid" >
                                   <a id="addMoreWorkEx" <?php if ($count == 10){?> style="display:none;"<?php }?> href="javascript:void(0);" onclick="addMoreWork('<?=$regFormId?>')"><i class="icons1 ic_addwrk"></i>Add More Work Experience</a>
                              </div> 
                              <div class="prf-btns">
                                  <section class="rght-sid">
                                   <a href="javascript:void(0);" onclick="shikshaUserProfileForm['<?=$regFormId?>'].submitForm('cancel');" class="btn-grey">Cancel</a>
                                   <a href="javascript:void(0);" onclick="if(validateTotalCurrentJob('<?php echo $regFormId;?>')){shikshaUserProfileForm['<?=$regFormId?>'].submitForm('save');} trackEventByGA('UserProfileClick','LINK_SAVE_WORK_EXPERIENCE');" class="btn_orngT1">Save</a>
                                  </section>
                             </div>
                              <p class="clr"></p>
                              <input type="hidden" name="sectionType" id="sectionType_<?=$regFormId?>" value="workExperienceSection" />
                              <input type="hidden" name="isStudyAbroad" value="<?php echo $isStudyAbroadFlag; ?>" />
                              <input type="hidden" name="abroadSpecialization" value="<?php echo $abroadSpecializationFlag; ?>" />
                           </form>
                        </div>
                       </section>


<?php


  for ($var=1; $var <= $count; $var++) { 
    $department = "department_".$var;
    $designation = "designation_".$var;
    $employer = "employer_".$var;

    $field[$department] = 'TextField';
    $field[$designation] = 'TextField';
    $field[$employer] = 'TextField';

  }

 
?>


<script type="text/javascript">
  
    var globalArray =[]; 
    var intialCount = <?php echo $count?>;

    var total =10;
    var workCount =parseInt('<?php echo $count;?>')+1;

    if(typeof(shikshaUserProfileForm) == 'undefined'){
      var shikshaUserProfileForm = {};
    }
    if(typeof(shikshaUserRegistrationForm) == 'undefined'){
      var shikshaUserRegistrationForm = {};
    }

    var field = <?php echo json_encode($field)?>;

    var i;
    for (i=1 ;i<=intialCount; i++) {
        globalArray.push(i);
    } 

    shikshaUserProfileForm['<?php echo $regFormId; ?>'] = new ShikshaUserProfileForm('<?php echo $regFormId; ?>');
   //shikshaUserRegistrationForm['<?php echo $regFormId; ?>'] = new ShikshaUserRegistrationForm('<?php echo $regFormId; ?>');
    shikshaUserProfileForm['<?php echo $regFormId; ?>'].setCustomValidations(<?php echo json_encode($field); ?>);
    shikshaUserProfileForm['<?php echo $regFormId; ?>'].setFormFieldList(<?php echo json_encode(array_keys($field)); ?>);  

  function addMoreWork(regFormId){

        var fieldId = workCount;
        globalArray.push(fieldId);

        var fields ={};

        for (var i = 0; i < globalArray.length; i++) {  

          var departmentKey = 'department_'+globalArray[i];
          var designation= 'designation_'+globalArray[i];
          var employer = 'employer_'+globalArray[i];
                    
            fields[employer]   = 'TextField' ;
           fields[departmentKey]   =  'TextField';
           fields[designation]   ='TextField'  ;
           
        };

      shikshaUserProfileForm[regFormId].setCustomValidations(fields);
      shikshaUserProfileForm[regFormId].setFormFieldList(Object.keys(fields));

       var workHtml ='<ul id="section_'+fieldId+'" class="p-ul"><div class="remove-lnk"><a href="javascript:void(0);" onclick="removeSection('+fieldId+')">Delete</a></div><li class="p-l"><span class="p-s"><label>Employer Name<i>*</i></label><input type="text"  regFieldId="employer_'+fieldId+'" onblur="shikshaUserProfileForm[\''+regFormId+'\'].validateField(this);" maxlength =150 mandatory="1" profanity="1" label="Employer Name"  default="Employer Name" caption="your Employer Name" id="employer_'+fieldId+'_'+regFormId+'" value= "" name= "employer_'+fieldId+'" class="prf-inpt" /><span><div class="regErrorMsg" id="employer_'+fieldId+'_error_'+regFormId+'"></div></span></span><span class="p-s"><label>Designation<i>*</i></label><input type="text" regFieldId="designation_'+fieldId+'" onblur="shikshaUserProfileForm[\''+regFormId+'\'].validateField(this);" maxlength =150 mandatory="1" profanity= "1" label="Designation" default="Designation" caption="your Designation" id= "designation_'+fieldId+'_'+regFormId+'" value="" name="designation_'+fieldId+'"  class="prf-inpt" /><span ><div class="regErrorMsg" id="designation_'+fieldId+'_error_'+regFormId+'"></div></span></span></li><li class="p-l"><span class="p-s"><label>Function/Department</label><input type="text" regFieldId="department_'+fieldId+'" onblur="shikshaUserProfileForm[\''+regFormId+'\'].validateField(this);"  maxlength =150 profanity= "1" label="Department" default="Department" caption="your Department" id= "department_'+fieldId+'_'+regFormId+'" value="" name="department_'+fieldId+'" class="prf-inpt" /><span><div class="regErrorMsg" id="department_'+fieldId+'_error_'+regFormId+'"></div></span></span><span class="p-s"><label>Is this your Current Job? </label><span class="prf-r"><input type="radio" name= "'+fieldId+'" id="currentJobYes_'+fieldId+'_'+regFormId+'" onclick="setCurrentJob(\''+fieldId+'\',\'YES\',\''+regFormId+'\')" value="" class="prf-inputRadio" ><label for="currentJobYes_'+fieldId+'_'+regFormId+'" class="prf-radio"> <i class="icons ic_radiodisable1"></i>Yes</label></span><span class="prf-r"><input type="radio" name= "'+fieldId+'" id="currentJobNo_'+fieldId+'_'+regFormId+'" onclick="setCurrentJob(\''+fieldId+'\',\'NO\',\''+regFormId+'\')"  value="NO"  checked="true" class="prf-inputRadio"><label for="currentJobNo_'+fieldId+'_'+regFormId+'" class="prf-radio"> <i class="icons ic_radiodisable1"></i>No</label></span></span><input type="hidden" value="1" name=workExp[] /><input id="currentJob_'+fieldId+'" type="hidden" value="NO" name=currentJob[] /></li><p class="clr"></p></ul>';



    if(globalArray.length == (total)){
      $j('#addMoreWorkEx').hide();
    }

     $j('#workExAction').append(workHtml);
     $j('#workExpCount').val(workCount);
      workCount++;

  }

  function validateTotalCurrentJob(regFormid){

    var counter =0; 

    for (var i =0;i<globalArray.length; i++){

        if($j('#currentJobYes_'+globalArray[i]+'_'+regFormid).val() == 'YES'){

          counter++;
        }
    }

    if(globalArray.length == 0){ 
      $j('#workExAction').append('<input type="hidden" name="workExp[]" value="1">');
    }

    if(counter >1){
      alert("Current job cannot be more than one ");
      return false;
    } else{
      return true;
    }

  }

</script>