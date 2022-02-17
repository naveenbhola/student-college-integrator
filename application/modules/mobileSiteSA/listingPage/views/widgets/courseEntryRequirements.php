<?php
$otherExams = array();
$hiddenClass = '';//'class="hiddenExamData" style="display:none"';
?>
<section class="detail-widget navSection" id="entryReqSection" data-enhance="false">
    <div class="detail-widegt-sec">
        <div class="detail-info-sec">
            <strong>Entry Requirements</strong>
            <ul>
                <?php               
                if(count($courseApplicationEligibilityDetails[0])>0){
                if($courseApplicationEligibilityDetails[0]['12thCutoff']!="0" && $courseApplicationEligibilityDetails[0]['12thCutoff']!="-1" ){ ?>
                <li>
                    <strong>Class 12th: <?php echo ($courseApplicationEligibilityDetails[0]['12thCutoff'])."%"; ?></strong>
                    <?php if($courseApplicationEligibilityDetails[0]['12thcomments']!=""){ ?>
                        <ul class="entry-req-list">
                            <li><div class="dynamic-content"><?php echo($courseApplicationEligibilityDetails[0]['12thcomments']);?></div></li>
                        </ul>
                    <?php } ?>
                    <div class="clearfix"></div>
                </li>
                <?php } ?>               
                <?php if($courseApplicationEligibilityDetails[0]['bachelorCutoff']!="0.00" && $courseApplicationEligibilityDetails[0]['bachelorCutoff']!="-1.00"){ ?>        
                <li>
                    <strong>Bachelors: <?php
                            if($courseApplicationEligibilityDetails[0]['bachelorScoreUnit']=="Percentage")echo intval($courseApplicationEligibilityDetails[0]['bachelorCutoff'])."%";
                            else echo $courseApplicationEligibilityDetails[0]['bachelorCutoff']." ".$courseApplicationEligibilityDetails[0]['bachelorScoreUnit']; ?>
                    </strong>
                    <?php if($courseApplicationEligibilityDetails[0]['bachelorComments']!=""){ ?>
                    <ul class="entry-req-list">
                        <li><div class="dynamic-content"><?php echo ($courseApplicationEligibilityDetails[0]['bachelorComments']); ?></div></li>
                    </ul>
                    <?php } ?>
                    <div class="clearfix"></div>
                </li>
                <?php } ?>               
                <?php if($courseApplicationEligibilityDetails[0]['pgCutoff']!=""){ ?>   
                <li>
                    <strong>PG cutoff: <span><?php echo $courseApplicationEligibilityDetails[0]['pgCutoff']."%"; ?></strong>
                    <?php if($courseApplicationEligibilityDetails[0]['pgComments']!=""){ ?>
                    <ul class="entry-req-list">
                        <li><div class="dynamic-content"><?php echo($courseApplicationEligibilityDetails[0]['pgComments']); ?></div></li>
                    </ul>
                    <?php } ?>
                    <div class="clearfix"></div>
                </li>
                <?php } }?>
<?php                
                    $examCounter = 0;
                    foreach($courseObj->getEligibilityExams() as $exam){
                        if($exam->getId() == -1){
                                array_push($otherExams, $exam);
                                continue;
                        }
                         ++$examCounter;   
                    ?>
                    <li <?=($examCounter>1)?$hiddenClass:''?>>
                        <strong style="margin-bottom:20px;"><?=$exam->getName()?></strong>
                        <?php   if($exam->getCutOff()!="N/A"){
                                if(is_numeric($exam->getCutOff())){
                                    $cutOffPercent = (($exam->getCutOff() - $exam->getMinScore())/($exam->getMaxScore() - $exam->getMinScore()))*100;
                                }else{
                                    $scoreArray = explode(',', strrev($exam->getRange()));
                                    $cutOffPercent = ((array_search($exam->getCutOff(), $scoreArray)+1)/(array_search($exam->getMaxScore(), $scoreArray)+1))*100;
                                }  ?>  
							<div class="exam-bar">	
                            <div class="exam-bar-percentage" style="width: <?=$cutOffPercent.'%'?>">
                            <?php   $cuttoffLen = strlen($exam->getCutOff());
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
                                } ?>
                                <p class="percent-box" style="color:#fff !important;<?=$styleRight?>">
                                    <?=$exam->getCutOff()?> <i class="sprite percent-pointer"></i>
                                </p>
                            </div>
                            </div>
                            <p class="exam-bar-info"><span class="flLt"><?=$exam->getMinScore()?></span><span class="flRt"><?=$exam->getMaxScore()?></span></p>
                            <?php }else{
                                    $examScoreRelatedText = $exam->getName().' is accepted but exact exam score is not published by the college.';  ?>
                            <p><?php echo $examScoreRelatedText; ?></p>
                            <?php }  ?>
                            <p class="exam-comment-info"><div class="dynamic-content"><div class="clearfix"></div><?=$exam->getComments()?></div></p>
                            <div class="clearfix"></div>
                        </li>
                    <?php }  foreach($otherExams as $otherExamObj){
                            ++$examCounter;  ?>   
                        <li <?=($examCounter>1)?$hiddenClass:''?>>
                            <label style="color: rgb(51, 51, 51); font-weight: bold; display: inline-block; width: 80%;"><?php echo htmlentities($otherExamObj->getName()); ?></label><?php echo htmlentities($otherExamObj->getCutOff()); ?>
                            <p class="exam-comment-info"><?php echo htmlentities($otherExamObj->getComments()); ?></p>
                        </li> 
                <?php  }?>
                <?php if($courseApplicationEligibilityDetails[0]['workExperniceValue']!="" && $courseApplicationEligibilityDetails[0]['workExperniceValue']!="0" ){ ?>
                <li <?=($examCounter>1)?$hiddenClass:''?>>
                    <strong>Work Exp.</strong>
                    <strong><?php echo $courseApplicationEligibilityDetails[0]['workExperniceValue']; ?> years</strong>
                    <?php if($courseApplicationEligibilityDetails[0]['workExperinceDescription']!="" && $courseApplicationEligibilityDetails[0]['workExperniceValue']!="" && $courseApplicationEligibilityDetails[0]['workExperniceValue']!="0"){ ?>
                    <ul class="entry-req-list">
                        <li class=""><div class="dynamic-content"><?php echo($courseApplicationEligibilityDetails[0]['workExperinceDescription']); ?></div></li>
                    </ul>
                    <?php } ?>
                    <div class="clearfix"></div>
                </li>
                <?php } ?> 
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
        <?php 
            $brochureDataObj['pageTitle'] = $courseObj->getName();
            $brochureDataObj['userRmcCourses'] = $userRmcCourses;
            $brochureDataObj['widget']      = 'courseEntryRequirement';
            if($courseObj->showRmcButton()){
        ?>
        <div class="rate-chances-sec">
            <p>Interested in this course?</p>
			<?php  $brochureDataObj['trackingPageKeyId'] = 429;
             $rateMyChanceCtlr->loadRateMyChanceButton($brochureDataObj); ?>
        </div>
        <?php } ?>
        
    </div>
</section>
