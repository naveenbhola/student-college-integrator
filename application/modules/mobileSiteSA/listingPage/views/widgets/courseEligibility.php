<?php
$otherExams = array();
$hiddenClass = '';//'class="hiddenExamData" style="display:none"';
?> <section class="detail-widget navSection" id="eligibilitySection">
        <div class="detail-widegt-sec">
            <div class="detail-info-sec">
                <strong>Entry Requirements</strong>
                <ul><?php
                        $examCounter = 0;
                        foreach($courseObj->getEligibilityExams() as $exam){
                            if($exam->getId() == -1){
                                array_push($otherExams, $exam);
                                continue;
                            }
                            ++$examCounter; ?>
                    <li <?=($examCounter>2)?$hiddenClass:''?>>
                            <strong style="margin-bottom:20px;"><?=$exam->getName()?></strong>
                            <?php
                                if($exam->getCutOff()!="N/A"){
                                    if(is_numeric($exam->getCutOff())){
                                        $cutOffPercent = (($exam->getCutOff() - $exam->getMinScore())/($exam->getMaxScore() - $exam->getMinScore()))*100;
                                    }else{
                                        $scoreArray = explode(',', strrev($exam->getRange()));
                                        $cutOffPercent = ((array_search($exam->getCutOff(), $scoreArray)+1)/(array_search($exam->getMaxScore(), $scoreArray)+1))*100;
                                    }
                            ?>
                            
                            <div class="exam-bar">	
                                <div class="exam-bar-percentage" style="width: <?=$cutOffPercent.'%'?>">
                                    <?php 
                                        $cuttoffLen = strlen($exam->getCutOff());
                                        if(strpos($exam->getCutOff(), '.')){
                                            $cuttoffLen -= 1;
                                        }
                                        switch($cuttoffLen){
                                            case    1 : $styleRight = 'right:-7px';
                                                        break;
                                            case    2 : $styleRight = 'right:-10px';
                                                        break;
                                            case    3 : $styleRight = 'right:-14px';
                                                        break;
                                            case    4 : $styleRight = 'right:-19px';
                                                        break;
                                    }
                                    ?>
                                    <p class="percent-box" style="color:#fff !important;<?=$styleRight?>">
                                        <?=$exam->getCutOff()?> <i class="sprite percent-pointer"></i>
                                    </p>
                                </div>
                            </div>
                            <p class="exam-bar-info"><span class="flLt"><?=$exam->getMinScore()?></span><span class="flRt"><?=$exam->getMaxScore()?></span></p>
                            <?php
                                }else{
                                    $examScoreRelatedText = $exam->getName().' is accepted but exact exam score is not published by the college.';
                            ?>
                            <p><?=$examScoreRelatedText?></p>
                            <?php    }     ?>
                            <p class="exam-comment-info dynamic-content"><?=$exam->getComments()?></p>
                            <div class="clearfix"></div>
                        </li>
                    <?php    }
                             foreach($otherExams as $otherExamObj){
                            ++$examCounter; ?>   
                        <li <?=($examCounter>2)?$hiddenClass:''?>>
                            <label style="color: rgb(51, 51, 51); font-weight: bold; display: inline-block; width: 80%;"><?=$otherExamObj->getName()?></label><?=$otherExamObj->getCutOff()?>
                            <p class="exam-comment-info dynamic-content"><?=$otherExamObj->getComments()?></p>
                        </li>
                                         
                <?php    }
                ?>
                <?php 
                    $courseAttr = $courseObj->getAttributes();
                    if($courseAttr['examRequired']){
                ?>
                <li <?=$hiddenClass?>>
                        <strong style="margin-bottom:3px;">Additional Info</strong>
                        <p><?=$courseAttr['examRequired']->getValue()?></p>
                    </li>
                <?php   }
                ?>
                
            </ul>
            <div  class="more-app-process-sec" style="margin:0;">
            <strong>Learn more study abroad exams</strong>
            <ul class="apply-content-widget rightExamWidget">
                <?php $k=0;
                      foreach($eligibilityExams as $examObj){
                      if($k==5)break; ?>
                <?php if($examWithGuide[$examObj->getId()]['contentURL']!=""){ ?>
                <li><strong style="display:block; padding-bottom:10px; float:none;"><a href="<?php echo $examWithGuide[$examObj->getId()]['contentURL']; ?>"><?php echo htmlentities($examObj->getName());?></a></strong></li>
                <?php $k++; } } ?>
            </ul>
            </div>
        </div>       
    </div>
</section>
