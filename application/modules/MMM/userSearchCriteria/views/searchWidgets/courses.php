<td class="nxt-td">
    <div class="nxt">
        <label class="label">Course:</label>
    </div>
</td>

<td class="">
    <div class="ul-block">
        <ul class="">

            <?php 
            $popularCourses = array_keys($courses['PopularCourses']);
            if(!empty($courses)) { 

                foreach($courses as $levelName=>$levelDetails) { 

                    $levelId = str_replace(" ", "", $levelName);

                    if($levelId == 'PopularCourses') {

                        if(!empty($levelDetails)) { ?>


                            <li>
                                <div class="Customcheckbox2">
                                    <input type="checkbox" id="<?php echo 'level'.$levelId.'_'.$criteriaNo;?>" class="clone loadExams" isParentEntity="1" entityType="level"/>
                                    <label for="<?php echo 'level'.$levelId.'_'.$criteriaNo;?>" class="clone">Popular Courses</label>
                                </div> 

                                <div class="l-col">
                                    <ul class="">
                                
                                        <?php
                                        foreach($levelDetails as $courseId=>$courseDetails) { ?>
                                        <li>
                                            <div class="Customcheckbox2">
                                                <input type="checkbox" id="<?php echo 'popularcourse'.$courseId.'_'.$criteriaNo;?>" value="<?php echo $courseId;?>" parentId="<?php echo 'level'.$levelId.'_'.$criteriaNo;?>" class="loadExams <?php echo 'level'.$levelId.'_'.$criteriaNo;?> courses_<?php echo $criteriaNo;?> clone" entityType="courses" isParentEntity="0" isPopularCourseChild="1"/>
                                                <label for="<?php echo 'popularcourse'.$courseId.'_'.$criteriaNo;?>" class="clone"><?php echo $courseDetails['name'];?></label>
                                            </div> 
                                        </li>
                                        <?php } ?>
                            
                                    </ul>
                                </div> 
                            </li>
                        
                        <?php 
                        }                        

                    } else {
                
                        if(!empty($levelDetails)) { ?>

                            <li>
                                <div class="Customcheckbox2">
                                    <input type="checkbox" id="<?php echo 'level'.$levelId.'_'.$criteriaNo;?>" class="clone loadExams" isParentEntity="1" entityType="level"/>
                                    <label for="<?php echo 'level'.$levelId.'_'.$criteriaNo;?>" class="clone"><?php echo $levelName;?></label>
                                </div> 

                                <div class="l-col">
                                    <ul class="">

                                        <?php
                                        //foreach($levelDetails as $level => $courseDetails) {
                                            foreach($levelDetails as $courseId=>$courseName) { ?>
                                            
                                            <li>
                                                <div class="Customcheckbox2">
                                                    <input type="checkbox" id="<?php echo 'course'.$courseId.'_'.$criteriaNo;?>" value="<?php echo $courseId;?>" parentId="<?php echo 'level'.$levelId.'_'.$criteriaNo;?>" class="loadExams <?php echo 'level'.$levelId.'_'.$criteriaNo;?> courses_<?php echo $criteriaNo;?> clone" entityType="courses" isParentEntity="0" <?php if(in_array($courseId, $popularCourses)) { echo 'isPopularCourse="1"'; } else { echo 'isPopularCourse="0"'; } ?> />
                                                    <label for="<?php echo 'course'.$courseId.'_'.$criteriaNo;?>" class="clone"><?php echo $courseName;?></label>
                                                </div> 
                                            </li>

                                        <?php } 
                                        //} ?>

                                    </ul>
                                </div> 

                            </li>

                        <?php 
                        } 
                    }
                } 

            } ?>

        </ul>
    </div>
</td>
