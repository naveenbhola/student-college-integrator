<div class="userpage-container">

 <form class="prf-form" id="registrationForm_<?=$regFormId?>">

  <div class="page-heading header-fix">
    <a href="#" class="p-title">Add/Edit Work Experience</a>
      <a href="#" data-rel="back" class="cls-head flRt">&times;</a>
  </div>
   <div class="workexp-dtls">
   	
   	<div id="workExTemp">

   	 <!-- <input id="workExpCount" type="hidden" value="" name='workExpCount' /> -->

     <input id="isNewWorkExp" type="hidden" value="" name='isNewWorkExp' />
     
   	 <?php 				
     if($addMoreWorkEx == "true") {

        $workExNextCounter = $workExCountGlobal+1;
        $workExLevelData = array();
        $workExLevelData['fieldId'] = $workExNextCounter;
        $workExLevelData['regFormId'] = $regFormId;
        $count =$workExNextCounter;   //done for validations (mandatory, profanity)
        $this->load->view('addWorkExSection',$workExLevelData);

     } else{

        $count =0;
        $currentValueFlag = false;

        for ($i=1; $i <=10 ; $i++) { 
          $workExLevel = 'workExp'.$i;
          $workExLevelData = $$workExLevel;

           $workExLevelData['fieldId'] = $i;
           $workExLevelData['regFormId'] = $regFormId;

          if(!empty($workExLevelData)&& !empty($workExLevelData['Employer'])){ 
            $count++;
             
            $this->load->view('addWorkExSection',$workExLevelData);
          }
        }    
      }  

      ?>  
  	</div>
  	
    
    <article class="workexp-box workexp-cont">
       	<div id ='addMoreWorkEdit' style='<?php if ($count >=10){ ?>display:none<?php } ?>' class="dtls ">
    	   	<a class="add-sec add-workEx" href="#">
    		    <i  class="profile-sprite addplus-icon flLt"></i>
    		      Add Work Experience
    	   	</a>
    	</div>
    </article>  

    	<input type="hidden" name="sectionType" id="currentJobFlag" value='' />
    </div> 	

   
    <input type="hidden" name="sectionType" id="sectionType_<?=$regFormId?>" value="workExperienceSection" />
    <input type="hidden" value="1" name='workExp[]' />
    <input type='hidden' name='isStudyAbroad' id="isStudyAbroadFlag_<?php echo $regFormId; ?>" value='<?php echo $isStudyAbroadFlag;?>' />
    <input type='hidden' name='abroadSpecialization' id="abroadSpecialization_<?php echo $regFormId; ?>" value='<?php echo $abroadSpecializationFlag;?>' />
    <input type="button" class="common-btn bottom-fix workExperienceSave" value="save" />

  </form>
</div>

<?php 
  $field = array();
  if($addMoreWorkEx == "true"){
    $var=$count;
  } else{
     $var=1;
  }
  
   for ($var; $var <= $count; $var++) { 
    $department = "department_".$var;
    $designation = "designation_".$var;
    $employer = "employer_".$var;

    $field[$department] = '';
    $field[$designation] = '';
    $field[$employer] = '';

  }

?>

<script>

  <?php if($addMoreWorkEx == "true") { ?>
    var workExpCounter = '<?php echo $workExCountGlobal+1;?>';
  <?php } else { ?>
    var workExpCounter = '<?php echo $count;?>'; 
  <?php } ?>

    var globalArray =[]; 

    var itr;
    for (itr=1 ;itr<=workExpCounter; itr++) {
        globalArray.push(itr);
    }

    var shikshaUserProfileForm = {};
    shikshaUserProfileForm['<?php echo $regFormId; ?>'] = new ShikshaUserProfileForm('<?php echo $regFormId; ?>');
   //shikshaUserRegistrationForm['<?php echo $regFormId; ?>'] = new ShikshaUserRegistrationForm('<?php echo $regFormId; ?>');
    shikshaUserProfileForm['<?php echo $regFormId; ?>'].setCustomValidations(<?php echo json_encode($field); ?>);
    shikshaUserProfileForm['<?php echo $regFormId; ?>'].setFormFieldList(<?php echo json_encode(array_keys($field)); ?>);  
</script>