<?php
$univFlag = false;
if($searchTupleType=='university'){
    $univFlag = true;
}
$courseCount = count($courseData);
?>
<!--slider start-->
   <div class="moreOptns clearwidth">
       <?php 
       if($univFlag){
      ?>
        <div class="textAbs"><?php echo $courseCount; echo ($courseCount>1)? ' Courses' : ' Course';?> Available</div>
      <?php 
       }
       ?>
       <div class="maxData">
         <div class="carouselDiv <?php echo ($univFlag)?'srpMoreCourses':'srpMoreCoursesC'; ?>" >
           <a class="clickLeft hid" > <i class="srpSprite ia"></i> </a>
           <a class="clickRight hid" > <i class="srpSprite ia"></i> </a>
           <ul class="courseData  <?php echo ($univFlag)?'ulpData':''; ?> clearwidth">
               <?php echo ($univFlag)?'':'<li class="dfltTxt">More Courses</li>'; ?>
               <?php
               $count=0;
               foreach ($courseData as $cId => $course)
               {
                   $courseName = $univFlag? ($course['cName']):($course['cN']);
                   $courseUrl = $univFlag? $course['cSeoUrl']:'javascript:void(0);';
                   if(!$univFlag)
                   {
                       if($count == 0)
                            $liAttr = 'class="active courseSliderTuple tl" loc="sclink" lid="'.$cId.'"';
                       else
                           $liAttr = 'class="courseSliderTuple tl" loc="sclink" lid="'.$cId.'"';
                       $liAttr .= ' cId="'.$univId.'_'.$cId.'"';
                   }
                   else
                   {
                       $liAttr = 'class="tl" loc="cinuniv" lid="'.$course['cId'].'"';
                   }
                   ?>
                   <li <?php echo $liAttr;?> ><div><a href="<?php echo $courseUrl; ?>" title="<?php echo ($courseName);?>"><?php echo strlen($courseName)>46?(substr(($courseName),0,43).'...'):($courseName);?></a><?php echo ($univFlag)?'<i class="srpSprite icLrw"></i>':''; ?></div></li>
                    <?php
                   $count++;
               }
               ?>
           </ul>
          </div>
       </div>
   </div>
 <!--slider ends-->