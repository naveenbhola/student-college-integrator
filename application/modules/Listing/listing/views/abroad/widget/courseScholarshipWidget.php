<?php
            $courseLevelLink    = $courseObj->getScholarshipLinkCourseLevel();
            $deptLevelLink      = $courseObj->getScholarshipLinkDeptLevel();
            $univLevelLink      = $courseObj->getScholarshipLinkUniversityLevel();
            
            if(!(0===strpos($courseLevelLink,'http')) && $courseLevelLink )
                  $courseLevelLink = "http://".$courseLevelLink;
            
            if(!(0===strpos($deptLevelLink,'http')) && $deptLevelLink )
                  $deptLevelLink = "http://".$deptLevelLink;
            
            if(!(0===strpos($univLevelLink,'http')) && $univLevelLink )
                  $univLevelLink = "http://".$univLevelLink;
            
        ?>
<div class="consultant-box clearwidth">
        	<div style="position:relative" class="consultant-que-box clearwidth">
            	<i class="listing-sprite stu-scholarship-icon"></i><strong class="stu-scholarship-head">Student scholarships at this <br>university</strong>
                <i style="left:17px;" class="listing-sprite consultant-pointer"></i>
            </div>
            <div class="consultant-contact-sec">
            	<ul class="level-list">
                	<?php if($courseLevelLink!='') {?><li><a target="_blank" rel="nofollow" class="gaTrack" gaParams="ABROAD_COURSE_PAGE,outgoingLink" href="<?php echo $courseLevelLink; ?>">Course Level on university website</a></li>      <?php }?>
					<?php if($deptLevelLink!='')   {?><li><a target="_blank" rel="nofollow" class="gaTrack" gaParams="ABROAD_COURSE_PAGE,outgoingLink" href="<?php echo $deptLevelLink;   ?>">Department Level on university website</a></li>  <?php } ?>
                    <?php if($univLevelLink!='')   {?><li><a target="_blank" rel="nofollow" class="gaTrack" gaParams="ABROAD_COURSE_PAGE,outgoingLink" href="<?php echo $univLevelLink;   ?>">University Level on university website</a></li>  <?php } ?>
                </ul>
            </div>
</div>