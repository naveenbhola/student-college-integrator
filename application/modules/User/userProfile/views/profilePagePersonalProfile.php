<div class="prf-tabpane" id="tab_0">
                      
    <!-- personal information-->
    <?php if($publicProfile == true && !empty($additionalInfo['Bio']) || !empty($personalInfo['Country'])){ ?>
        <div id="personalInformationSection">
        	<?php $this->load->view('profilePagePersonalInformation');?> 
        </div>
    <?php } else if($publicProfile != true){ ?>
        <div id="personalInformationSection">
            <?php $this->load->view('profilePagePersonalInformation');?> 
        </div>
    <?php } ?>

    <!--educational background-->
    <?php
    if($UG['CourseCompletionDate']){
      $date =  $UG['CourseCompletionDate']->format('Y-m-d H:i:s');
      $completionDateUG = substr($date, 0, 4);
    }
    if($xth['CourseCompletionDate']){
      $date =  $xth['CourseCompletionDate']->format('Y-m-d H:i:s');
      $completionDatexth = substr($date, 0, 4);
    }
    if($xiith['CourseCompletionDate']){
      $date =  $xiith['CourseCompletionDate']->format('Y-m-d H:i:s');
      $completionDatexiith = substr($date, 0, 4);
    }


        $flagEdu = false;
        if( !empty($xth['InstituteName']) || !empty($xiith['InstituteName']) || !empty($UG['InstituteName']) || !empty($PG['InstituteName']) || !empty($PHD['InstituteName']) || (!empty($completionDateUG) && $completionDateUG !=' -000') || (!empty($completionDatexth) && $completionDatexth !=' -000') || ( !empty($completionDatexiith) && $completionDatexiith !=' -000') ){
            $flagEdu = true;
        }
     if($publicProfile == true && $flagEdu == true){ ?>
        <div id="educationalBackgroundSection">
        	<?php $this->load->view('profilePageEducationBackground'); ?>
        </div>
    <?php } else if($publicProfile != true){ ?>
        <div id="educationalBackgroundSection">
            <?php $this->load->view('profilePageEducationBackground'); ?>
        </div>
    <?php } ?>
    

    <!--work experience-->
    <?php
    $flag = false;
    for ($i=1; $i <=10 ; $i++) { 
        $workExLevel = 'workExp'.$i;
        $workExLevelData = $$workExLevel;

        if(!empty($workExLevelData)){
            $flag= true;
            break;
        }
    }
    if($publicProfile == true && (!empty($personalInfo['Experience']) || $personalInfo['Experience']=='0' || $personalInfo['Experience']=='-1' )|| $flag == true){ ?>
        <div id="workExperienceSection">
           <?php $this->load->view('profilePageWorkExperience');?>
        </div>
    <?php } else if($publicProfile != true){ ?>
        <div id="workExperienceSection">
           <?php $this->load->view('profilePageWorkExperience');?>
        </div>
    <?php } ?>
        
    <!--educational preferences-->
    <?php
     if($publicProfile == true && (!empty($desiredCourseDetails['courseName']) || !empty($domesticInterestDetails))) { ?>
        <div id="educationalPreferenceSection">
             <?php $this->load->view('profilePageEducationPreference'); ?>
        </div>
    <?php } else if($publicProfile != true){ ?>
    <div id="educationalPreferenceSection">
             <?php $this->load->view('profilePageEducationPreference'); ?>
        </div>
    <?php } ?>
         
</div>