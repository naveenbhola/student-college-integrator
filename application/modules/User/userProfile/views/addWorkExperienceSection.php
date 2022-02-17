
                    <ul  id="section_<?php echo $fieldId?>" class="p-ul"> 
            <div class="remove-lnk"><a href="javascript:void(0);" onclick="removeSection('<?php echo $fieldId; ?>');">Delete</a></div>

                                    <li class="p-l">
                                      <span class="p-s">
                                         <label>Employer Name<i>*</i></label>
                                         <input type="text" regFieldId="employer_<?php echo $fieldId;?>" onblur="shikshaUserProfileForm['<?php echo $regFormId; ?>'].validateField(this);" maxlength =150 mandatory="1" profanity="1" label="Employer Name"  default="Employer Name" caption="your Employer Name" id="employer<?php echo '_'.$fieldId.'_'.$regFormId?>" value= "<?php echo $Employer;?>"  name="employer_<?php echo $fieldId;?>" class="prf-inpt" />
                                        <span > <div class="regErrorMsg" id="employer<?php  echo '_'.$fieldId.'_error_'.$regFormId;  ?>"></div>  </span>                                    
                                      </span>

                                       <span class="p-s">
                                         <label>Designation<i>*</i></label>
                                         <input type="text" regFieldId="designation_<?php echo $fieldId;?>" onblur="shikshaUserProfileForm['<?php echo $regFormId; ?>'].validateField(this);" maxlength =150  mandatory="1" profanity= "1" label="Designation" default="Designation" caption="your Designation" id= "designation<?php echo '_'.$fieldId.'_'.$regFormId?>" label="Designation" value="<?php echo $Designation;?>" name="designation_<?php echo $fieldId;?>" class="prf-inpt" />
                                         <span > <div class="regErrorMsg" id="designation<?php echo '_'.$fieldId.'_error_'.$regFormId; ?>"></div> </span >
                                      </span>
                                       
                                    </li>

                                    <li class="p-l">
                                      <span class="p-s">
                                         <label>Function/Department</label>
                                         <input type="text" regFieldId=name="department_<?php echo $fieldId;?>"  onblur="shikshaUserProfileForm['<?php echo $regFormId; ?>'].validateField(this);" maxlength =150 profanity= "1" label="Department" default="Department" caption="your Department"  id= "department<?php echo '_'.$fieldId.'_'.$regFormId?>" value="<?php echo $Department;?>" name="department_<?php echo $fieldId;?>" class="prf-inpt" />
                                         <span > <div class="regErrorMsg" id="department<?php echo '_'.$fieldId.'_error_'.$regFormId; ?>"></div> </span >
                                      </span>


                                       <span class="p-s">
                                         <label>Is this your Current Job? </label>
                                         <span class="prf-r">
                                            <input type="radio" id="currentJobYes<?php echo '_'.$fieldId.'_'.$regFormId?>" onclick="setCurrentJob('<?php echo $fieldId; ?>','YES','<?=$regFormId?>')" name ="<?php echo $fieldId;?>"   <?php if ($CurrentJob == 'YES'){ echo 'checked="true"';?> value="YES" <?php } ?>   class="prf-inputRadio" >
                                            <label for="currentJobYes<?php echo '_'.$fieldId.'_'.$regFormId?>" class="prf-radio"> <i class="icons ic_radiodisable1"></i>Yes</label>
                                         </span>

                                         <span class="prf-r">
                                            <input type="radio" id="currentJobNo<?php echo '_'.$fieldId.'_'.$regFormId?>" onclick="setCurrentJob('<?php echo $fieldId; ?>','NO','<?=$regFormId?>')" name ="<?php echo $fieldId;?>" value="NO" <?php if ($CurrentJob != 'YES'){echo 'checked="true"'; } ?> class="prf-inputRadio">
                                            <label for="currentJobNo<?php echo '_'.$fieldId.'_'.$regFormId?>" class="prf-radio"> <i class="icons ic_radiodisable1"></i>No</label>
                                         </span>

                                      </span>
                                        <input type="hidden" value="1" name='workExp[]' />
                                        <input id="currentJob_<?php echo $fieldId;?>" type="hidden" value="<?php if ($CurrentJob == 'YES'){ echo 'YES';} else { echo 'NO';}?>" name='currentJob[]' />
                                    </li>
                                     <p class="clr"></p>
                                   </ul>


<script>
  function setCurrentJob(fieldId,value,regId){

    if(value == 'YES'){
      
         $j('#currentJobYes_'+fieldId+'_'+regId).val(value);
    } else{
      $j('#currentJobYes_'+fieldId+'_'+regId).val("");
    }
   

  }
  
  function removeSection(fieldId){

    fieldId = parseInt(fieldId);
    var index = globalArray.indexOf(fieldId);
/*
    if(fieldId ==1){
      index =0;
    } else if(fieldId ==2 && globalArray.length ==1){
      index =0;
    }*/

    if (index > -1) {
        globalArray.splice(index, 1);
    }      


     var fields ={};

        for (var i = 0; i < globalArray.length; i++) {  

          var departmentKey = 'department_'+globalArray[i];
          var designation= 'designation_'+globalArray[i];
          var employer = 'employer_'+globalArray[i];
                    

           fields[departmentKey]   =  'TextField';
           fields[designation]   ='TextField'  ;
           fields[employer]   = 'TextField' ;

        };


    shikshaUserProfileForm['<?php echo $regFormId; ?>'].setCustomValidations(fields);
    shikshaUserProfileForm['<?php echo $regFormId; ?>'].setFormFieldList(Object.keys(fields));

     $j('#section_'+fieldId).remove();
     $j('#addMoreWorkEx').show();
     $j('#workExpCount').val(workCount-1);
}

  
</script>
