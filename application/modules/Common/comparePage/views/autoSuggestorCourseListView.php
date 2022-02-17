<?php
$j = 3;
$instituteId = $institute->getId();
$courses     = $institute->getCourse();
if( isset( $courses ) && count($courses)>0 ){
        $j++;
?>
<div class="cmpre-drpdwn">
   <a href="javascript:;" class="customCourse" courseTupple="<?php echo $j?>" id="customCourse<?php echo $j?>"><i class="cmpre-sprite ic_arrow"></i><span class="display-area">Select a Course</span></a>
   <div class="custm-drp-layer" id="customCourseList<?php echo $j?>">
        <ul>
        <?php
        foreach ($courses as $courseData){
          // primary institute of course
          $instituteName = '';
          if(empty($courseData))
          {
            continue;
          }
          if($courseData->getInstituteId() != $instituteId){
            $instituteName = ($courseData->getInstituteShortName() !='') ? $courseData->getInstituteShortName() : $courseData->getInstituteName();   
          }
          ?>
          <li courseId="<?php echo $courseData->getId()?>" courseTupple="<?php echo $j?>"><a href="javascript: void(0);"><?php echo $courseData->getName();if($courseData->getOfferedById() != '' && $instituteId != $courseData->getOfferedById() &&  $courseData->getOfferedByShortName() != '' ){ echo ', '.$courseData->getOfferedByShortName();}?></a></li>
          <?php 
        }
        ?>
        </ul>
   </div>
</div>
<input type="hidden" id="courseSelect<?=$j?>" value=""/>
<?php } ?>
