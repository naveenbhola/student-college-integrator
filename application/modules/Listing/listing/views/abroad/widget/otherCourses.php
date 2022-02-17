<?php
 if(count($otherCoursesArr['courses']) == 1){
            $title = 'Similar course at '.htmlentities($universityObj->getName());
        }else{
            $title = 'Similar courses at '.htmlentities($universityObj->getName());
        }?> 
<div class="consultant-box clearwidth">
            <div style="position:relative" class="consultant-que-box clearwidth">
                <i class="listing-sprite similar-course-icon-2"></i><strong class="stu-scholarship-head"><?php echo $title; ?></strong>
                <i style="left:17px;" class="listing-sprite consultant-pointer"></i>
            </div>
            <div class="consultant-contact-sec">
                <ul>
                    <?php $i=0;
                          $j=(count($otherCoursesArr['courses']))-1;
                          //echo $j;
                          foreach($otherCoursesArr['courses'] as $courseObj){
                            if($i==5)break;
                           // echo $i;
                        ?>
                        <li <?php if($i==$j){echo 'class="last"'; } ?>>
                        <a target="_blank" href="<?php echo $courseObj->getURL();?>"><?php echo htmlentities($courseObj->getName());?></a>
                        <div class="course-info-list-new">
                            <?php if($otherCoursesArr['coursesData'][$courseObj->getId()]['fees']!='') 
                                  { ?>
                                    <p><label>1st year total fees:</label><?php echo $otherCoursesArr['coursesData'][$courseObj->getId()]['fees']; ?></p>
                            <?php }  
                                  if(!empty($otherCoursesArr['coursesData'][$courseObj->getId()]['eligibilityExam'])) 
                                  {?>
                                    <p><label>Eligibility:</label>
                            <?php   $k=0;
                                    foreach($otherCoursesArr['coursesData'][$courseObj->getId()]['eligibilityExam'] as $examName => $examCutOff)
                                    { 
                                       if($k>0)
                                       {
                                        echo ", ";
                                       } 
                                       echo htmlentities($examName).":".$examCutOff;
                                       $k++;
                                    } ?></p>
                            <?php } ?>
                        </div>
                    </li>
                    <?php $i++;
                } ?>
                </ul>
            </div>
        </div>