<?php
$j = 3;
$courses = $institute->getCourses();
if( isset( $courses ) && count($courses)>0 ){
        $j++;
?>
<div class="cmpre-drpdwn">
   <a href="javascript:;" class="customCourse" courseTupple="<?php echo $j?>" id="customCourse<?php echo $j?>"><i class="cmpre-sprite ic_arrow"></i><span class="display-area">Select Course</span></a>
   <div class="custm-drp-layer" id="customCourseList<?php echo $j?>">
        <ul>
        <?php 
        foreach ($courses as $courseD){
          ?>
          <li courseId="<?php echo $courseD->getId()?>" courseTupple="<?php echo $j?>"><a href="javascript:;"><?php echo $courseD->getName()?></a></li>
          <?php 
        }
        ?>
        </ul>
   </div>
</div>
<input type="hidden" id="courseSelect<?=$j?>" value=""/>
<?php } ?>
