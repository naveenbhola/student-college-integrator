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
    $selectionText = $courseCount." course".($courseCount==1?"":"s")." found in this university"; 
?>

<strong class="font-14" id="courseResultLabel">Found <?=$courseCount?> course<?=(courseCount>1?'s':'')?> for:</strong>
<div class="find-again-sec">
    <strong class="flLt" id="courseSelectionText"><?=$selectionText?></strong>
    <a class="flRt" href="javascript:void(0);" onclick="findCoursesAgain();">
        <strong>Find Again</strong>
    </a>
    <div class="clearfix"></div>
</div>

<div style="margin-bottom:0;" class="updated-pop-courses-list">
    <div class="scrollbar1 fat-scrollbar1">
        <div class="scrollbar">
            <div style="height:320px;" class="track">
                <div class="thumb"></div>
            </div>
        </div>
        <div class="viewport" style="height:400px;">
            <div class="overview" style="width:100% !important">
                <ul style="width:98%;">
                    <?php foreach($courses as $course){ ?>
                    <li class="courseRow <?=$course['level'] == 'Certificate - Diploma'?'Certificate':$course['level']?> <?=$course['stream']?>">
                        <a href="<?=($universityCourseBrowseSectionByStream['urls']['courses'][$course['course_id']])?>" target="_blank"><?=htmlentities($course['courseTitle'])?></a>
                        <p>
                            <label>1st year total fees:</label>
                            <span style="margin-right:10px;"><?=($universityCourseBrowseSectionByStream['fees'][$course['course_id']])?></span>
                            <label>Eligibility:</label>
                            <span>
                                <?php
                                    $count = 0;
                                    foreach($universityCourseBrowseSectionByStream['exams'][$course['course_id']] as $exam){
                                        if($exam->getId() != -1){   ?>
                                            <?php $count++; ?>
                                            <?php if($count == 2){
                                                echo "|";
                                            }?>
                                            <?=htmlentities($exam->getName())?>:<?=$exam->getCutOff()=="N/A"?"Accepted":$exam->getCutOff()?>
                                        <?php
                                            
                                            if($count == 2){
                                                break;
                                            }
                                        }
                                    }
                                ?>
                            </span>
                        </p>
                    </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
</div>