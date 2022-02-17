<div class="course-detail-tab eligibility-tab overview-details clearfix">
    <div class="course-detail-mid flLt">
        <h2 style="margin:0 0 10px 0;" class="course-require-hd">Entry requirements for this course</span></h2>
        <div style="margin-top:0px;" class="clearwidth cons-scrollbar1 scrollbar1 soft-scroller">
            <div class="cons-scrollbar scrollbar" style="visibility:hidden; left:8px;">
                <div class="track">
                    <div class="thumb"></div>
                </div>
            </div>
            <div style="height:300px" class="viewport">
                <div class="overview dyanamic-content entry-req-list">
                   
                    <?php if(count($courseApplicationEligibilityDetails)>0){ ?>
                        <?php if($courseApplicationEligibilityDetails[0]['12thCutoff']!="0"){ ?>
                            <p class="course-require-hd">Class 12th: <span><?php echo ($courseApplicationEligibilityDetails[0]['12thCutoff']=="-1")?"No specific cutoff mentioned":($courseApplicationEligibilityDetails[0]['12thCutoff'])."%"; ?></span></p>
                            <?php if($courseApplicationEligibilityDetails[0]['12thcomments']!=""){ ?><div><?php echo($courseApplicationEligibilityDetails[0]['12thcomments']);?></div><?php } ?>
                        <?php } ?>
                        
                        <?php if($courseApplicationEligibilityDetails[0]['bachelorCutoff']!="0.00"){ ?>
                            <p class="course-require-hd">Bachelors: <span><?php
                                if($courseApplicationEligibilityDetails[0]['bachelorCutoff'] =="-1.00") echo "No specific cutoff mentioned";
                                else if($courseApplicationEligibilityDetails[0]['bachelorScoreUnit']=="Percentage")echo intval($courseApplicationEligibilityDetails[0]['bachelorCutoff'])."%";
                                else echo $courseApplicationEligibilityDetails[0]['bachelorCutoff']." ".$courseApplicationEligibilityDetails[0]['bachelorScoreUnit']; ?>
                            </span></p>
                            <?php if($courseApplicationEligibilityDetails[0]['bachelorComments']!=""){ ?><div><?php echo ($courseApplicationEligibilityDetails[0]['bachelorComments']); ?></div><?php } ?>
                        <?php } ?>
                        
                        <?php if($courseApplicationEligibilityDetails[0]['pgCutoff']!=""){ ?>
                            <p class="course-require-hd">PG cutoff: <span><?php echo $courseApplicationEligibilityDetails[0]['pgCutoff']."%"; ?></span></p>
                            <?php if($courseApplicationEligibilityDetails[0]['pgComments']!=""){ ?><div><?php echo($courseApplicationEligibilityDetails[0]['pgComments']); ?></div><?php } ?>
                        <?php } ?>
                    
                        <?php if( $courseApplicationEligibilityDetails[0]['workExperniceValue']!="0" ){ ?>
                        <p class="course-require-hd">Work experience: <span><?php echo ($courseApplicationEligibilityDetails[0]['workExperniceValue']=="")?"No work experience mentioned":$courseApplicationEligibilityDetails[0]['workExperniceValue']."years"; ?></span></p>
                        <?php } ?>
                        
                        <?php if($courseApplicationEligibilityDetails[0]['workExperinceDescription']!="" && $courseApplicationEligibilityDetails[0]['workExperniceValue']!="0"){ ?>
                        <div>
                        <?php echo($courseApplicationEligibilityDetails[0]['workExperinceDescription']); ?>
                        </div>
                        <?php } ?>
                    
                    <?php } ?>
                         <!--Shiksha Apply Main Content Starts Here-->
                    <?php foreach($eligibilityExams as $examObj){?>
                    <?php if($examObj->getId()!= -1){?>
                    <div class=" course-seekbar clearwidth">
                        <div class="coverbar">
                        <?php
                                $curExamURL = $examWithGuide[$examObj->getId()]['contentURL'];
                                $href = empty($curExamURL)?"":$curExamURL;
                            ?>
                            <a  class="flLt" <?php if(!empty($href)){?>href="<?php echo $href; ?>" target="_blank"<?php } ?>>
                                    <?php echo htmlentities($examObj->getName());?>
                                    <i class="listing-sprite course-cutoff-pointer"></i>
                            </a>
                            
                            
                            <?php if($examObj->getCutOff()!="N/A"){ ?>
                                    <div class="flLt eligibility-cutoff course-cutoff">
                                        <div class="completed-bar completed-seekbar" style="width:200px"></div>
                                        <div class="completed-percen course-complt-perc">
                                            <span class="num-cutoff"><?php echo($examObj->getCutOff())?></span>
                                            <div class="course-seekbar-nov"></div>
                                        </div>
                                    </div>
 <div class="max-cutof course-cutoff-lst flLt"><?=($examObj->getMaxScore())?></div>

                                    <?php
                                    if(!is_numeric($examObj->getMaxScore())){
                                        $range = count(explode(",",$examObj->getRange()));
                                        $marksCutoffActual = array_search($examObj->getCutOff(),array_reverse(explode(",",$examObj->getRange())))+1;
                                    }
                                    else{
                                        $range = $examObj->getRange();
                                        $marksCutoffActual = $examObj->getCutOff();
                                    }
                                    ?>
                                    <input type = "hidden" class = "marksRange" value = "<?=($range)?>" />
                                    <input type = "hidden" class = "marksCutoffActual" value = "<?=($marksCutoffActual)?>" />
                                <?php }
                                 else{  ?>
                                <div class="flLt" style="width: 275px; margin-right: 32px; vertical-align: top; margin-top: -4px;font-size: 11px; margin-left:22px;">
                                    <?=$examObj->getName()?> is accepted but exact exam score is not published by the college.
                                </div>
                            <?php }?>
                        </div>
                    </div>
                    <?php }
                    else{ ?>
                    <!--Custom exam-->
                    <div class="course-seekbar clearwidth">
                        <div>
                            <label class="flLt">
                                <?php echo htmlentities($examObj->getName());?>
                                <i class="listing-sprite course-cutoff-pointer"></i>
                            </label>
                            <span class="custom-exam-cutoff"><?php echo($examObj->getCutOff())?></span>
                        </div>
                    </div>
                    <?php } ?>
                    
                    <div class="clearwidth exam-requiremnt-list">
                    <?php if($examObj->getComments()!=""){?>
                    <?php echo($examObj->getComments()); ?>
                    <?php } ?>
                    </div>
                     <!--ends-->
                    <?php } ?>
                    <!--additional information-->
                    <?php
                            $courseAttr = $courseObj->getAttributes();
                            $examInfo   = $courseAttr['examRequired'];
                    if($applicationProcessData['additionalRequirement']!="" &&  $applicationProcessDataFlag){?>
                    <p class="course-require-hd clearwidth">Additional info</p>
                    <div class="clearwidth"><?php  echo($applicationProcessData['additionalRequirement']); ?></div>
                    <?php }
                    else if(!$applicationProcessDataFlag && $examInfo!=""){?>
                    <p class="course-require-hd clearwidth">Additional info</p>
                    <div class="clearwidth"><?php echo($examInfo->getValue()); ?></div>
                    <?php } ?>
                    <!--end-->
                    <div class="clearwidth report-info">
                        <a href="https://studyabroad.shiksha.com/which-exam-to-give-for-which-course-to-study-abroad-articlepage-536" target="_blank" >Which exams out of the above do I need to give?</a>
                    </div>
 <div class="clearwidth report-info">
                        <a href="javaScript:void(0);" onclick="reportIncorrect('reportIncorrectContainer','Eligibilitytab','<?= $listingTypeId?>');">Report incorrect information</a>
     </div>
                </div>
             </div>
        </div>
    </div>
    <!--right side grey tab data-->
    <div class="course-detail-rt flRt clearfix">
        <div style="padding:10px 7px; width:100%; float:left;" class="course-rt-tab">
            <ul class="app-process-ryt-list rightExamWidget">
                <strong class="font-11" style="color:#333;">Learn more about exams</strong>
                <!--eligibilty exams-->
                <li style="padding-top: 5px; padding-bottom:5px;">
                 <?php $k=0;
                        foreach($eligibilityExams as $examObj){
                        //if($k==5)break; ?>
                   <?php if($examWithGuide[$examObj->getId()]['contentURL']!=""){ ?>
                   
                        <a style="text-decoration:none;" href="<?php echo $examWithGuide[$examObj->getId()]['contentURL']; ?>" target="_blank"><?php echo htmlentities($examObj->getName());?></a>
                        <?php if($k < count($eligibilityExams)-1){ echo " <span class='sprtr'>|</span> ";}?>                 
                <?php $k++; } } ?>
                </li>
                <!--eligibilty exams ends-->
            </ul>
        </div>
        <!--Apply Home Linking widget -->
        <?php 
        $linkingWidgetData = array('gaParams'=>'COURSEPAGE_ELIGIBILITY_TAB,applyPageLinkingWidget',
                                    'applyLinkWidgetTitle' => 'Have you given a exam?',
                                    'applyLinkWidgetDesc'  => 'Get shortlist of universities from expert Shiksha counselors based on your exam score'
                                    );
        $this->load->view('listing/abroad/widget/applyHomeLinkingWidget',$linkingWidgetData);
        ?>

        <!--This rate my chance button-->
        <?php   //$param['widget'] = 'eligibilityTab';
                //$param['trackingPageKeyId'] = 368;
                //$this->load->view('listing/abroad/widget/rateMyChanceWidget',$param);
        ?>
 </div>
    <!--Ends here right grey tab data-->
</div>

