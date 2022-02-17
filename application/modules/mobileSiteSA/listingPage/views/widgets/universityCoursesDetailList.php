<?php
   $courses = array();
   foreach($universityCourseBrowseSectionByStream['stream'] as $stream=>$levels)
   {
      foreach($levels as $level=>$courseData)
      {
         $courseData['courses'] = array_map(function($a) use($stream,$level){
                                                 $a['stream'] = $stream;
                                                 $a['level'] = $level;
                                                 return $a;
                                             },$courseData['courses']);
         $courses = array_merge($courses,$courseData['courses']);
      }
   }
   $courseCount = count($courses);
   // sort by view count
   usort($courses,function($a,$b)use($allCourseViewCounts){
      return ($allCourseViewCounts[$a['course_id']]>=$allCourseViewCounts[$b['course_id']]?-1:1);
   });
   $selectionText = "<strong>".$courseCount." course".($courseCount==1?"":"s")." found in this university"."</strong>";
?>    

<div class="available-course-detail clearfix">
   <p style="border-bottom: 1px solid rgb(224, 224, 224); margin-bottom: 10px; padding-bottom: 4px;">
      <span style="display:inline-block; margin-bottom:5px;">
         <span id="resultCourseCount"><?=($selectionText)?></span>
      </span>
      <a href="javascript:void(0);" onclick="showFindCourseWidget();" style="float: right;">Find Again</a>
   </p>
   <ul class="univ-detail-list" id="findCourseListContainer">
      <?php foreach($courses as $course) { ?>    
      <li class="<?=$course['level'] == 'Certificate - Diploma'?'Certificate':$course['level']?> <?=$course['stream']?> courseRow">
         <div class="univ-detail-section">
            <a href="<?=($universityCourseBrowseSectionByStream['urls']['courses'][$course['course_id']])?>" style="font-weight:bold; width:90%; display:block; word-wrap:break-word;"><?= htmlentities(formatArticleTitle($course['courseTitle'],74));?></a>
            <div class="fee-eligibilty-criteria">
               <div class="fee-eligibilty-col">
                  <span>1st Year Total Fees</span>
                  <p><a href="<?=($universityCourseBrowseSectionByStream['urls']['courses'][$course['course_id']])?>" style="color:#333"><?=($universityCourseBrowseSectionByStream['fees'][$course['course_id']])?></a></p>
               </div>
               <div class="fee-eligibilty-col">
                  <span>Eligibility</span>
                  <p>
                  <a href="<?=($universityCourseBrowseSectionByStream['urls']['courses'][$course['course_id']])?>" style="color:#333">    
                  <?php
                  $count=1;
                      foreach ($universityCourseBrowseSectionByStream['exams'][$course['course_id']] as $examObj)
                      {
                          if($examObj->getId() != -1){
                              if($examObj->getCutoff() == "N/A"){
                                  $cutOffText = 'Accepted';
                              }else{
                                  $cutOffText = $examObj->getCutoff();
                              }
                              echo ($count==2)?" | ":"";            
                              echo htmlentities($examObj->getName().':'.$cutOffText);
                              if($count==2){
                                  break;
                              }
                              $count++;
                          }        
                  } ?>
                  </a>
                  </p>
               </div>
               <a href="<?=($universityCourseBrowseSectionByStream['urls']['courses'][$course['course_id']])?>" style="color:#333">
               <div class="fee-eligibilty-col" style="width:5%">
                  <i class="sprite univ-detail-arrw"></i>
               </div>
               </a>
            </div>
         </div>
      </li>
      <?php } ?>
   </ul>
   <a style="display: none;" id="findMoreCourses" class="load-more-courses" href="javaScript:void(0);" onclick="loadMoreCourseResults(this);"></a>
</div>