<?php 
$GA_Tap_On_Admission = 'ADMISSION_NAVIGATION';
$GA_Tap_On_Admission_Proc = 'ADMISSION_PROCESS_NAVIGATION';
?>
<div class="clg-nav">
    <div class="clg-navList">
        <ul>
          <?php if($instituteObj->getAdmissionDetails() != '' && $coursesData['mostPopularCourse']){ ?>
          <li><a elementtofocus="about-admission" href="javascript:void(0);" ga-attr="<?=$GA_Tap_On_Admission;?>">About Admissions</a></li>
          <li><a elementtofocus="admission-process" href="javascript:void(0);" ga-attr="<?=$GA_Tap_On_Admission_Proc;?>">Course Information</a></li>
           <?php } ?>
        </ul>
    </div>
</div>