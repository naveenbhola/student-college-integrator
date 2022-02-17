    <?php $extraInfo = getCourseTupleExtraData($courseObj,'courseDetail',false);?>

    <div class="group-card gap">
        <div>
            <div class="crs-crd">
            <!-- <a href="<?=$instituteURL?>"> -->
                <p class="para-L2"><a href="<?=$instituteURL?>" class="para-L2"><?=$instituteName?></a>  
                <?php  if($isMultilocation){?>&nbsp;<span href="javascript:void(0);" class="link multi-loc-link" ga-track="VIEWALLBRANCHES_TOP_COURSEDETAIL_DESKTOP">Change branch</span><?php } ?>
                <?php 
                if($validateuser != 'false') {
                   if($validateuser[0]['usergroup'] == 'cms'  || $validateuser[0]['usergroup'] == 'sums' || $validateuser[0]['usergroup'] == 'saAdmin' || $validateuser[0]['usergroup'] == 'saCMS'){
                    if(!empty($courseIsPaid)){
                        echo '<span class="upc-tag">PAID</span>';
                    }else{
                        echo '<span class="upc-greentag">FREE</span>';
                    }
                   }
                }                
                ?> 
                </p>                
                <?php 
                    $shortlistActiveClass = '';
                    $shortlistText = 'Shortlist';
                    if(!empty($isCourseShortlisted[$courseId])) {
                        $shortlistActiveClass = 'active';
                        $shortlistText = 'Shortlisted';
                    }
               
                ?>
                <a tracking-id="936" class="shrt-list shortlist-site-tour addToShortlist <?=$shortlistActiveClass;?>" ga-track="SHORTLIST_COURSEDETAIL_DESKTOP"><?=$shortlistText?>
                    <span style="display: none;" class="srpHoverCntnt"><p><?php echo $websiteTourContentMapping['Shortlist']; ?></p></span>
                </a>
            </div>
            <div class="crs-detl">
                <?php 
                    $courseNameClass = '';
                    if(!empty($courseDates)) {
                        $courseNameClass = 'short-wdth';
                    }

                ?>
                <div class="<?=$courseNameClass;?>">
                <h1><?=$courseName?></h1>
                <span>
                    <?=$extraInfo?>
                    <div class="inlineCol">
                    <?php
                    //Aggregate Review Widget Code
                        if(!empty($courseWidgetData[$courseId]) && $courseWidgetData[$courseId]['aggregateRating']['averageRating'] > 0)
                        { 
                            echo "| ";
                            $this->load->view("listingCommon/aggregateReviewInterlinkingWidget", array('allReviewsUrl' => $all_review_url,'aggregateReviewsData' =>$courseWidgetData[$courseId] ,'reviewsCount' => $courseObj->getReviewCount(), 'isPaid' => $courseObj->isPaid(),'forPredictor'=>1));
                        }
                        if($anaWidget['count'] > 0){ 
                        echo "| ";
                        ?>
                            <a class="view_rvws  qstn" ga-attr="Header_AnsweredQuestions_COURSEDETAIL_DESKTOP" href="<?php echo $anaWidget['allQuestionURL']?>" target="_blank">
                             <i class="qstn_ico"></i><?php echo formatNumber($anaWidget['count'])?> Answered Questions
                            </a>
                        <?php
                        }
                    ?>
                    </div>
                    </span>
                </div>

                <?php 
                if(!empty($courseDates)) 

                {?>
                <div class="reg-card">
                    <?php 
                        if($courseDates['type'] == 'onlineForm') { 
                            $ctaName = 'Start Application';
                            $onlineFormUrl = $courseDates['url']."?tracking_keyid=".DESKTOP_NL_COURSE_PAGE_TOP_APPLY_OF;
                            $ctaLink = 'data-href="'. $courseDates['url']."?tracking_keyid=".DESKTOP_NL_COURSE_PAGE_TOP_APPLY_OF .'" ga-track="APPLYNOW_COURSEDETAIL_DESKTOP" data-type="onlineForm" ';
                            $dateText = $courseDates['eventName'];
                        }
                        else if($courseDates['type'] == 'importantDates') {
                            $ctaName = 'View all Dates';
                            //$ctaLink = 'onclick="animateTodiv(\'#admissions\')")';
                            $ctaLink = 'ga-track="VIEWALLDATES_COURSEDETAIL_DESKTOP" data-type="viewDatesForm"';
                            $dateText = $courseDates['eventName'];
                        }
                        //_p($dateText);die('qqqq');

                    ?>
                    <div class="evnt-blc">
                    <?=$dateText;?>
                    <!-- <div class="tooltip left">
                        <div class="tooltip-arrow"></div>
                        <div class="tooltip-inner">
                            Clicking here will redirect you to the college page
                        </div> 
                    </div>-->
                    <?php
                        if(!empty($courseDates['date']) ) { 
                            ?>
                            <span><?=$courseDates['date']?> 
                                <?php
                                if($courseDates['type'] != 'onlineForm'){ ?>
                                    <a class="CTA-aplyNow" <?=$ctaLink?>>
                                        <?=$ctaName;?>
                                    </a>
                                <?php } ?> 
                                    
                            </span>
                            
                    <?php }
                    ?>
                    </div>
                    <?php 
                    if($courseDates['type'] == 'onlineForm'){ ?>
                        <button type="button" onClick = "window.location=('<?php echo $onlineFormUrl;?>')" <?=$ctaLink?> class="oafcta-btn"><i class="d-arrow"></i><?=$ctaName;?></button>
                    <?php } ?>
                    

                </div>
                <?php } ?>
            </div>
            <div class="crs-det-info">
                <div class="crs-detBox">
                <?php 

                    $recognitions = $recognitionData['approvals'];
                    $recognitionsCount = count($recognitions);
                        ?>
                <?php 
                $showAffiliationCount = 0;
                    if(!empty($recognitions)) { 
                        $showAffiliationCount++;
                        ?>
                        <div class="crs-detCol">
                            <?=$recognitions[0]['name'];?>
                            <span>
                            Approved
                            <div class="tp-block">
                                <i class="info-icn" infodata = "<?=$recognitions[0]['tooltip']?>" infopos="right"></i>
                            </div> 
                            </span> 
                        <?php
                            if($recognitionsCount > 1) { ?>
                                    <a class="link popLayer">+<?=$recognitionsCount-1;?> more</a>
                                    <div class="speech hid speech-bottom news-agency" type="medium_of_instruction" style="top: -17px;width:240px !important;">
                                        <div class="speech-head">Recognition <a class="close-head" id="cl-s"></a></div>
                                        <div class="speech-cont">
                                            <ul class="agency-msg-lst">
                                                <?php
                                                    for($i=1;$i<$recognitionsCount;$i++) { ?>
                                                        <li>
                                                            <?php 
                                                            $toolTipData = ''; 
                                                            if($recognitions[$i]['tooltip'])
                                                                $toolTipData = " : <span>".$recognitions[$i]['tooltip']."</span>";

                                                            ?>
                                                            <p><?=$recognitions[$i]['name'].$toolTipData;?></p>
                                                        </li>
                                                <?php 
                                                    }
                                                ?>
                                            </ul>
                                        </div>
                                    </div>
                            <?php }
                            ?>
                        </div>
                <?php }
                ?>
                    
                    <?php
                        if(!empty($recognitionData['institute'])) { 
                            foreach ($recognitionData['institute'] as $value) { 
                                $showAffiliationCount++;
                                ?>
                                <div class="crs-detCol"><?=$value['name']?> <span>Accredited
                                <?php if($value['tooltip']){?>
                                    <div class="tp-block">
                                        <i class="info-icn" infodata = "<?=$value['tooltip']?>" infopos="right"></i>
                                    </div>
                                <?php } ?>
                                </span>
                                </div>
                    <?php   } ?>
                    <?php }
                    ?>
                    
                    <?php if(!empty($fees) && $fees['totalFeesBasicSection']) {
                        $showAffiliationCount++;
                        ?>
                        <div class="crs-detCol">
                            <?=$fees['totalFeesBasicSection'];?> 
                            <span>
                                Total Fees 
                                <?php 
                                    if(!empty($fees['feesTooltipBasicInfo'])){
                                        ?>
                                        <div class="tp-block">
                                            <i class="info-icn" infodata = "<?=$fees['feesTooltipBasicInfo']?>" infopos="right"></i>
                                        </div>
                                        <?php
                                    }
                                ?>
                            </span>
                        </div>
                    <?php } ?>
                    
                    <?php 
                        if(!empty($courseRankTopWidget['courseRankData'])) { 
                            $showAffiliationCount++;
                            $courseRankData = $courseRankTopWidget['courseRankData'];
                            ?>
                            <div class="crs-detCol">Rank #<?=$courseRankData[0]['rank'];?> <span>by <a href="<?=$courseRankData[0]['url']?>"><?=($courseRankData[0]['source_name']." ".$courseRankData[0]['source_year']);?></a> </span>
                            <?php
                            $countCourseRankData = count($courseRankData);
                                if($countCourseRankData > 1) { ?>
                                    <a class="link popLayer">+<?=$countCourseRankData-1;?> more</a>
                                    <div class="speech hid speech-bottom news-agency" type="medium_of_instruction" style="top: -17px;width:245px !important;">
                                        <div class="speech-head">Rank <a class="close-head" id="cl-s"></a></div>
                                        <div>
                                            <div class="speech-cont">
                                                <ul class="agency-msg-lst">
                                                    <?php
                                                        for($i=1;$i<$countCourseRankData;$i++) { 
                                                            if($courseRankData[$i]['type'] == 'location') break;
                                                            ?>
                                                            <li>
                                                                <p><span>Rank</span> #<?=$courseRankData[$i]['rank']?> <span>by</span> <a class="link" href="<?=$courseRankData[$i]['url']?>"><?=($courseRankData[$i]['source_name']." ".$courseRankData[$i]['source_year']);?></a></p>
                                                            </li>
                                                    <?php 
                                                        }
                                                    ?>
                                                </ul>
                                            </div>
                                            <div class="speech-cont speech-mt10">
                                                <ul class="agency-msg-lst">
                                                    <?php
                                                        $courseRankInterlinkData = $courseRankTopWidget['courseRankInterlinkData'];
                                                        if(!empty($courseRankInterlinkData)) {
                                                            foreach ($courseRankInterlinkData as $courseRankInterlinkValue) { ?>
                                                                <li>
                                                                    <p><span><?=$courseRankInterlinkValue['anchorText'];?></span> <a class="link" href="<?=$courseRankInterlinkValue['url']?>"><?=$courseRankInterlinkValue['locationName'];?></a></p>
                                                                </li>
                                                                
                                                        <?php }
                                                        }
                                                    ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                            <?php }
                            ?>
                            </div>
                    <?php }
                    ?>

                    <?php
                        $mediumOfInstruction = $courseObj->getMediumOfInstruction();
                        if(!empty($mediumOfInstruction)) { 
                            $showAffiliationCount++;
                            $mediumOfInstructionCount = count($mediumOfInstruction);
                            ?>
                        <div class="crs-detCol">
                            <?=$mediumOfInstruction[0]->getName(); ?>
                            <span>
                                Medium
                            </span>
                            <?php 
                                if($mediumOfInstructionCount > 1) { ?>
                                    <a class="link popLayer">+<?=$mediumOfInstructionCount-1;?> more</a>
                                    <div class="speech hid speech-bottom news-agency" type="medium_of_instruction" style="top: -17px;width:auto !important;">
                                        <div class="speech-head">Medium <a class="close-head" style="right:2px;" id="cl-s"></a></div>
                                        <div class="speech-cont">
                                            <ul class="agency-msg-lst">
                                                <?php
                                                    for($i=1;$i<$mediumOfInstructionCount;$i++) { ?>
                                                        <li>
                                                            <p><?=$mediumOfInstruction[$i]->getName();?></p>
                                                        </li>
                                                <?php 
                                                    }
                                                ?>
                                            </ul>
                                        </div>
                                    </div>
                            <?php }
                            ?>
                        </div>
                        <?php }
                        ?>

                        <?php  
                             $difficultyLevel = $courseObj->getDifficultyLevel()->getName(); 
                             if(!empty($difficultyLevel)) { 
                                $showAffiliationCount++;
                                ?> 
                                 <div class="crs-detCol"><?=$difficultyLevel;?> <span>Difficulty Level</span></div> 
                         <?php } 
                         ?> 
                    
                <?php 
                    if(!empty($affiliatedUniversityName)) { 
                        $affiliationClass = '';
                        $showInNewDiv = false;
                        if($showAffiliationCount > 2) {
                            $showInNewDiv = true;
                            $affiliationClass = 'new-line';
                        }
                        $targetAttr = '';
                        if($affiliatedUniversityScope == 'abroad') {
                            $targetAttr = 'target="_blank"';
                        }
                        $affiliatedHref = 'href="'.$affiliatedUniversityUrl.'"';
                        $affiliatedAnchor = "$affiliatedUniversityName";
                        if(!empty($affiliatedUniversityUrl)) {
                            $affiliatedAnchor = "<a class='para-L2' $targetAttr $affiliatedHref>$affiliatedUniversityName</a>";
                        }
                    if(!$showInNewDiv) { ?>
                        <div class="crs-detCol affliated-wdth"><p class="text-affliated">Affiliated to <?=$affiliatedAnchor?></p></div> 
                    <?php }
                        ?>
                <?php }
                ?>
                </div>
                <?php 
                    if(!empty($affiliatedUniversityName) && $showInNewDiv) { ?>
                        <div class="crs-detBox <?=$affiliationClass;?>">
                            <p class="text-affliated">Affiliated to <?=$affiliatedAnchor?></p>
                        </div>
                <?php }
                ?>
                <div class="crs-btnCol" id="CTASection">
                    <a tracking-id="<?=COMPARE_DESKTOP_CTA?>" class="btn-secondary addToCompare compare-site-tour" ga-track="COMPARE_COURSEDETAIL_DESKTOP">Add to Compare
                        <span style="display: none;" class="srpHoverCntnt"><p><?php echo $websiteTourContentMapping['Compare'] ?></p></span>
                    </a>
                    <?php $this->load->view('CoursePage/CourseDownloadBrochure',array('isSticky'=>false)); ?>
                </div>
            </div>
        </div>
    </div>