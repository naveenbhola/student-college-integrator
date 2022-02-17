<?php
    $extraInfo = getCourseTupleExtraData($courseObj,'mobileCoursePage',false);
?>
<div class="new-container panel-pad">
    <div class="lcard clg-panel">
        <div class="clg-panel-head">
            <p class="para-L3"><a href="<?php echo $instituteURL; ?>" class="para-L2"><?=($instituteName);?></a>
            <?php 
                if($validateuser != 'false') {
                   if($validateuser[0]['usergroup'] == 'cms' || $validateuser[0]['usergroup'] == 'enterprise' || $validateuser[0]['usergroup'] == 'sums' || $validateuser[0]['usergroup'] == 'saAdmin' || $validateuser[0]['usergroup'] == 'saCMS'){
                    if(!empty($courseIsPaid)){
                        echo '<span class="upcoming-tap">PAID</span>';
                    }else{
                        echo '<span class="upcoming-tap-greentag">FREE</span>';
                    }
                   }
                }                
            ?> 
            <?php if($isMultilocation){ ?>
                <a href="javascript:void(0);" class="link-blue-small chng-arw" onclick="gaTrackEventCustom('<?php echo $GA_currentPage;?>','<?php echo 'VIEWALLBRANCHES_TOP_COURSEDETAIL_MOBILE';?>','<?php echo $GA_userLevel;?>');showLocationLayer();">Change branch<i class="arw-icn"></i></a>
            <?php } ?>
            </p>
            <h1 class="head-L1">
                <?php echo ($courseName);?>
            </h1>
            <ul class="caption-list">
                <?=$extraInfo?>

            </ul>
        </div>
        <div class="clg-detail">
            <ul>
                <?php $elementsToShow = array(); ?>
                <?php 
                    foreach($recognitionData['approvals'] as $rec){
                            $str = "<span>".$rec['name']." Approved</span>";
                            if(count($recognitionData['approvals']) > 1){
                                $str .= " <span> <a href='javascript:void(0);' id='approvalsShowMore' ga-attr = 'APPROVALTOOLTIP_TOP_COURSEDETAIL_MOBILE' >+".(count($recognitionData['approvals'])-1)." More</a></span>";
                            }else{
                                $str .=" <a href='javascript:void(0);' id='approvalsShowMore' ga-attr = 'APPROVALTOOLTIP_TOP_COURSEDETAIL_MOBILE' ><i class='clg-sprite clg-info'></i></a>";
                            }
                            $elementsToShow[] = $str;
                            break;
                    }
                    foreach($recognitionData['institute'] as $rec){
                            $str = "<span>".$rec['name']." Accredited</span>";
                            if(count($recognitionData['institute']) > 1){
                                $str .= " <span><a href='javascript:void(0);' id='accreditedShowMore' ga-attr = 'ACCREDITIONTOOLTIP_TOP_COURSEDETAIL_MOBILE'>+".(count($recognitionData['institute'])-1)." More</a></span>";
                            }else{
                                $str .=" <a href='javascript:void(0);' id='accreditedShowMore' ga-attr = 'ACCREDITIONTOOLTIP_TOP_COURSEDETAIL_MOBILE' ><i class='clg-sprite clg-info'></i></a>";
                            }
                            $elementsToShow[] = $str;
                            break;
                    }
                    if(!empty($fees)) {
                        $str = "Total Fee <span>".$fees['totalFeesBasicSection']."</span>";
                        if(!empty($fees['feesTooltipBasicInfo'])){
                            $str .= " <a href='javascript:void(0);' ga-attr = 'FEESTOOLTIP_TOP_COURSEDETAIL_MOBILE' id='feesTooltip'><i class='clg-sprite clg-info'></i></a>";
                        }
                        $elementsToShow[] = $str;
                    }
                    $medium = $courseObj->getMediumOfInstruction();
                    $showCount = false;
                    if(count($medium) > 1){
                        $showCount = true;
                    }
                    $fmedium = $medium[0];
                    if($fmedium && $fmedium->getName() != "English" || $showCount){
                        if(!empty($fmedium)){
                            $str = "Medium <span>".$fmedium->getName();
                            $str .= "</span>";
                            if($showCount){
                                $str .= "<span> <a href='javascript:void(0);' id='mediumShowMore'>+".(count($medium)-1)." More</a></span>";
                            }
                            
                            $elementsToShow[] = $str;
                        }    
                    }
                    if($difficulty = $courseObj->getDifficultyLevel()->getName()){
                        $str = "Difficulty Level <span>".$difficulty."</span>";
                        $elementsToShow[] = $str;
                    }

                    echo "<li>";
                    foreach($elementsToShow as $element){
                        echo '<div class="clg-col">'.$element.'</div>';
                        $counter+=1;
                        if($counter %2 == 0){
                            echo '</li><li>';
                        }
                    }
                    echo "</li>";

                    // Now for the one liners
                    $elementsToShow = array();
                    $courseRankData = $courseRankTopWidget['courseRankData'];
                    $rank = $courseRankData[0];
                    if($rank){
                        $rankingAnchorTag = '<a href="'.$rank['url'].'" >'.$rank['source_name'].' '.$rank['source_year'].'</a>';
                        $rankString = "<span>Ranked ".$rank['rank']." by ".$rankingAnchorTag."</span>";    
                    }
                    if(count($courseRankData)>1){
                        $rankString .= " <span> <a href='javascript:void(0);' id='rankShowMore'>+".(count($courseRankData)-1)." More</a> </span>";
                    }
                    $elementsToShow[] = $rankString;
                    if(!empty($affiliatedUniversityName)){
                        $targetAttr = '';
                        if($affiliatedUniversityScope == 'abroad') {
                            $targetAttr = 'target="_blank"';
                        }
                        $affiliatedHref = 'href="'.$affiliatedUniversityUrl.'"';

                        $str = "Affiliated To ";
                        if(!empty($affiliatedUniversityUrl)) {
                            $elementsToShow[] = $str.'<a '.$targetAttr.'  '.$affiliatedHref.' class="para-L2 affiliated">'.$affiliatedUniversityName.'</a>';
                        }
                        else {
                            $elementsToShow[] = $str.$affiliatedUniversityName;
                        }
                    }
                    $counter = 0;
                    
                    foreach($elementsToShow as $element){
                        echo "<li>";
                        if(substr($element,0,10)=='Affiliated' || substr($element,0,12)=='<span>Ranked'){
                            echo '<div>'.$element.'</div>';
                        }
                        else{
                            echo '<div class="clg-col">'.$element.'</div>';    
                        }
                        
                        echo "</li>";    
                    }
                    
                    ?>

            </ul>
            <div id="basicInfoPopups">
                <div id="mediumShowMoreDiv" style="display:none;">
                    <?php
                        $tMedium = $medium;
                    ?>
                    <div class="glry-div amen-div">
                        <div class="hlp-info">
                            <div class="loc-list-col">
                                <div>
                                    <strong> Mediums of Education </strong>
                                </div>
                                <div class="prm-lst">
                                    <?php foreach($tMedium as $tmed){ ?>
                                        <div class="amen-box">
                                            <strong><?php echo $tmed->getName(); ?></strong>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="rankShowMoreDiv" style="display:none;">
                    <?php
                        $tRank = $courseRankData;
                    ?>
                    <div class="glry-div amen-div">
                        <div class="hlp-info">
                            <div class="loc-list-col">
                                <div class="prm-lst">
                                    <div class="amen-box">
                                        <p class="head-L3">Course Rankings</p>
                                        <ul class="n-more-ul rnk-pb5">
                                            <?php foreach($tRank as $rank){ ?>
                                                <li>Ranked <?=$rank['rank']?> by <a href="<?=$rank['url']?>"><?=($rank['source_name']." ".$rank['source_year']);?></a></li>
                                            <?php } ?>
                                        </ul>
                                        <ul class="n-more-ul">
                                            <?php 
                                                $courseRankInterlinkData = $courseRankTopWidget['courseRankInterlinkData'];
                                                if(!empty($courseRankInterlinkData)) {
                                                    foreach ($courseRankInterlinkData as $courseRankInterlinkValue) { ?>
                                                      <li><?=$courseRankInterlinkValue['anchorText'];?> <a href="<?=$courseRankInterlinkValue['url']?>"><?=$courseRankInterlinkValue['locationName'];?></a></li>
                                                <?php }
                                                } ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="approvalsShowMoreDiv" style="display: none;">
                    <div class="glry-div amen-div">
                        <div class="hlp-info">
                            <div class="loc-list-col">
                                <div class="prm-lst">
                                    <?php foreach($recognitionData['approvals'] as $rec){ ?>
                                        <div class="amen-box">
                                            <strong><?=$rec['name']?></strong>
                                            <p class="para-L3">
                                                <?=$rec['tooltip']?>
                                            </p>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="accreditedShowMoreDiv" style="display: none;">
                    <div class="glry-div amen-div">
                        <div class="hlp-info">
                            <div class="loc-list-col">
                                <div class="prm-lst">
                                    <?php foreach($recognitionData['institute'] as $rec){ ?>
                                        <div class="amen-box">
                                            <strong><?=$rec['name']?></strong>
                                            <p class="para-L3">
                                                <?=$rec['tooltip']?>
                                            </p>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="feesTooltipDiv" style="display: none">
                    <div class="glry-div amen-div">
                        <div class="hlp-info">
                            <div class="loc-list-col">
                                <div class="prm-lst">
                                    <div class="amen-box">
                                        <p class="head-L3">Total Fee</p>
                                        <p class="para-L3">
                                            <?=$fees['feesTooltipBasicInfo'];?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="durationTooltipDiv" style="display: none">
                    <div class="glry-div amen-div">
                        <div class="hlp-info">
                            <div class="loc-list-col">
                                <div class="prm-lst">
                                    <div class="amen-box">
                                        <p class="head-L3">Duration</p>
                                        <p class="para-L3">
                                            <?php echo DURATION_TOOLTIP; ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php if(!empty($courseDates)) {?>
                <div class="dot-div">
                    <?php 
                        if($courseDates['type'] == 'onlineForm') { 
                            $ctaName = 'Apply Now';
                            $ctaLink = "emailResults('".$courseObj->getId()."', '".base64_encode($courseObj->getInstituteName())."' , '".$courseDates['internalFlag']."' , '". MOBILE_NL_COURSE_PAGE_TOP_APPLY_OF."');";
                            $ctaGA = "ga-attr='APPLYNOW_COURSEDETAIL_MOBILE'";
                            $ctaId = "startApp".$courseObj->getId();
                            $dateText = $courseDates['eventName'];
                        }
                        else if($courseDates['type'] == 'importantDates') {
                            $ctaName = 'View all dates';
                            $ctaLink = "animateTodiv('#admissions')";
                            $ctaGA = 'ga-attr="VIEWALLDATES_COURSEDETAIL_MOBILE"';
                            $dateText = $courseDates['eventName'];
                        }
                            
                    ?>
                    <h2><?php echo $dateText;?></h2>
                    <p class="apply-t"><?=$courseDates['date']?><a id="<?=$ctaId?>" <?=$ctaGA?> onclick="<?=$ctaLink?>" class="link"><?=$ctaName;?></a></p>
                </div>
            <?php } ?>
        </div>
        <?php if(!empty($isCourseShortlisted[$courseId])){$isCourseShortlisted=1;}
              else {$isCourseShortlisted=0;}
        ?>
        <a class="btn-mob shortlistButton" id="topWidgetShortlist"><i class="<?php echo ($isCourseShortlisted == 0)?"non-":"";?>short-ico rating-primary none" ga-attr="SHORTLIST_COURSEDETAIL_MOBILE"></i><span>Shortlist<?php echo ($isCourseShortlisted == 1)?"ed":"";?></span></a>
    </div>
</div>
