<?php
//_p($abroadListingCommonLib);
?>
<div id="popularCoursesDiv">
    <strong class="font-14">Popular Course<?=count($popularCourses)==1?'':'s'?> at this University</strong>
    <div class="updated-pop-courses-list">
        <ul>
            <?php foreach($popularCourses as $course){?>
            <li>
                <a href="<?=$course->getURL()?>" target="_blank"><?=htmlentities($course->getName())?></a>
                <p>
                    <label>1st year total fees:</label>
                    <span style="margin-right:10px;"><?=$abroadListingCommonLib->getIndianDisplableAmount($abroadListingCommonLib->convertCurrency($course->getTotalFees()->getCurrency(), 1, $course->getTotalFees()->getValue()),1)?></span>
                    <label>Eligibility:</label>
                    <span>
                        <?php
                            $count = 0;
                            foreach($course->getEligibilityExams() as $exam){
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
