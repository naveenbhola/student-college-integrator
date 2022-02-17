<?php
    $courseDescription = new tidy ();    
    $courseDescription->parseString ($courseObj->getCourseDescription(), array ('show-body-only' => true ), 'utf8' );
    $courseDescription->cleanRepair();
    if(strlen($courseDescription) > 350)
        $courseDescriptionViewPortHeight = '165px;';
    else
       $courseDescriptionViewPortHeight = '190px;';
    
    if($courseRank)
        $courseRankLastClass = "course-dur-bdr";
    elseif($averageSalary)
        $averageSalaryLastClass = "course-dur-bdr";
    elseif($courseExamName)
        $courseExamNameLastClass = "course-dur-bdr";
    elseif($courseFeesDisplableAmount)
        $courseFeesDisplableAmountLastClass = "course-dur-bdr";
    else
        $courseDurationLastClass = 'course-dur-bdr';
    
?>
<div class="course-detail-tab overview-tab clearfix">
    <!-- Main content in course overview section  -->
    <div class="course-detail-mid flLt">
    
    <div id ="abroadCourseOverview" class="cons-scrollbar1 scrollbar1 clearwidth">
        <div class="cons-scrollbar scrollbar" style="visibility:hidden;">
            <div class="track">
                <div class="thumb"></div>
            </div>
        </div>
        <div class="viewport" style="height:300px">
            <div class="overview dyanamic-content" style="width:98%;">
            <?php 
                $courseDuration = $courseObj->getDuration();
                $courseLevel = $courseObj->getCourseLevel1Value();
                if(!empty($courseDuration)|| !empty($courseLevel)){?>
                <h2 style="margin:0px;">About this course</h2>
                <div>
					<?php 
                        if(!empty($courseDuration)) { echo "Duration: ".htmlentities($courseObj->getDuration()->getExactDurationValue().' '.$courseObj->getDuration()->getDurationUnit()); }
                        if(!empty($courseDuration) && !empty($courseLevel)) { echo " <span style='color:#999;'> | </span>"; }
                        if(!empty($courseLevel)) {
							echo "Level: ".$courseLevel." Program";
						}
					?>
                </div>
            <?php } ?>
                <h2><strong>Course Description</strong></h2>
                <p><?php echo $courseDescription; ?></p>
                <h2><strong>University offering this course</strong></h2>
                 <div class="univ-logo-img">
                    <a href="<?=$universityObj->getURL()?>"><img src="<?php echo $universityObj->getLogoLink(); ?>" alt="<?php echo htmlentities($universityObj->getName()); ?>"/></a>
                 </div>
                 <div class=" univ-logo-detail">
                    <p><a href="<?=$universityObj->getURL()?>"><?=formatArticleTitle(htmlentities($universityObj->getName()),70)?></a></p>
                    <p style="margin:3px 0;">
                        <?php  $universityType = $universityObj->getTypeOfInstitute();
                        echo ($universityType == "not_for_profit")?"Not for profit":ucfirst($universityObj->getTypeOfInstitute()); ?> University, Estd in <?php echo $universityObj->getEstablishedYear()?></p>
                    <p style="color:#999999; font-size:11px;"><?=$universityObj->getMainLocation()->getCity()->getName()?>, <?=$universityObj->getMainLocation()->getCountry()->getName()?></p>
                </div>
                <?php $this->load->view('listing/abroad/widget/universityAnnouncement'); ?>
            </div>
        </div>
    </div>        
    </div>
    <!-- main section ends here -->
<!-- Course Overview Right section -->
    <div class="course-detail-rt flRt clearfix">
        <div class="course-rt-tab">
            <ul class="course-dur">
                <?php   if($courseFeesDisplableAmount){?>
                        <li elementtofocus="leftnav-fees" class="<?=$courseFeesDisplableAmountLastClass?>">
                            <strong>1st year Fees &amp; expenses</strong>
                            <p><span class="fees-expenses"><?=$courseFeesDisplableAmount?></span><span><a href="javascript:void(0);" onclick="$j('#leftnav-fees').trigger('click')">View breakup</a></span></p>
                        </li>
                <?php   }     if($courseExamName){  ?>
                        <li class="<?=$courseExamNameLastClass?>">
                            <strong>Eligibility</strong>
                            <p><span class="fees-expenses"><?=$courseExamName." : ".(($courseExamScore=="N/A")?"Accepted":$courseExamScore)?></span><span><a href="javascript:void(0)" onclick="$j('#leftnav-eligibility').trigger('click');">View more</a></span></p>
                        </li>
                <?php   }  if($averageSalary){ ?>
                        <li class="<?=$averageSalaryLastClass?>">
                            <strong>Avg Salary</strong>
                            <p><span class="fees-expenses"><?=$averageSalary?></span><span><a href="javascript:void(0)" onclick="$j('#leftnav-placement').trigger('click');">View more</a></span></p>
                        </li>
                <?php   }  if($courseRank){ ?>
                        <li class="<?=$courseRankLastClass?>">
                            <strong>Rank</strong>
                            <p><span style="margin-right:5px;"><?=$courseRank?> in</span><span><a href="<?=$courseRankURL?>" target="_blank"><?=formatArticleTitle(htmlentities($courseRankName),30)?></a></span></p>
                        </li>
                <?php   }   ?>
            </ul>
        </div>
        <!-- Rate my chance right in right section -->
        <?php   $param['widget'] = 'overviewTab';
                $param['trackingPageKeyId'] = 369;
                $this->load->view('listing/abroad/widget/rateMyChanceWidget',$param);  ?>
    </div>
    <!-- Right section ends here -->
</div>
