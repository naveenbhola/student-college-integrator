<?php    
    $displayLocation = $displayDataObject[$course->getId()]->getDisplayLocation();
    $courseInfoString = $displayDataObject[$course->getId()]->getCourseInfoString();
    if(!empty($displayLocation)){
        $feesObj = $course->getFeesCategoryWise($displayLocation->getLocationId());
    }
    $locationSuffixForUrl = $displayDataObject[$course->getId()]->getLocationSuffixForUrl();

    $courseFees = '';
    $courseFeesUnit = '';
    
    if(!empty($feesObj['general'])){
        $courseFees = $feesObj['general']->getFeesValue();
        $courseFeesUnit = $feesObj['general']->getFeesUnitName();
    }
    
    unset($feesObj['general']);
    //get reviews
    $courseReviewCount = $course->getReviewCount();
    if($courseReviewCount == 1){
        $reviewText = "Review";
    }else{
        $reviewText = "Reviews";
    }
	//get questions
    if(!empty($questionsCountCombined[$course->getId()])){
        $questionCount = $questionsCountCombined[$course->getId()];
    }else{
        $questionCount = 0;
    }
    

    
    if($questionCount == 1){
        $questionText = "Question on this course";
    }else{
        $questionText = "Questions on this course";
    }

    global $TupleClickParams;
    $loadedCourseCount = $tupleCourseCount;
    if(!empty($loadedCourseCount)){
        $loadedCourseCount = $tuplenumber.'|'.$loadedCourseCount;
    }
    else{
        $loadedCourseCount = $tuplenumber;
    }
    if(DO_SEARCHPAGE_TRACKING && DO_TUPLE_TRACKING && in_array($product,array('AllCoursesPage','Category','SearchV2'))){
        $trackingstring = "{$TupleClickParams['from']}=serp&{$TupleClickParams['pagenum']}={$pageNumber}&{$TupleClickParams['tuplenum']}=$loadedCourseCount&{$TupleClickParams['clicktype']}=course&{$TupleClickParams['listingtypeid']}={$course->getId()}";
        if(!empty($trackingSearchId)){
            $trackingstring .= "&{$TupleClickParams['trackingSearchId']}={$trackingSearchId}";
            if(!empty($trackingFilterId)){
                $trackingstring .= "&{$TupleClickParams['trackingFilterId']}=".$trackingFilterId;
            }
        }
    }
?>

<h5 class="tpl-course-name"><a  class="tuple-course-name" target="_blank" href="<?php echo add_query_params($course->getURL().$locationSuffixForUrl,$trackingstring); ?>"><?php  echo htmlentities($course->getName())?>
<?php 
if(in_array($product,array('AllCoursesPage','Category','SearchV2'))){
    ?>
      <span style="display: none;" class="srpHoverCntnt"><p><?php echo $websiteTourContentMapping['Course'] ?></p></span>
          <?php
      }
      ?>
</a></h5>

<?php if(DEBUGGER) _p("Sorter Value: ".$debugSortInfo['courseDebugSortParam'][$course->getId()]); ?>

<div class="course-inf"><?php echo $courseInfoString; ?>
    <!--p>Popularity Score 78/100</p-->

<?php
$courseStatus = "";
if(!empty($courseStatusData[$course->getId()])){
    $courseStatus = implode(", ", $courseStatusData[$course->getId()]['courseStatusDisplay']);    
}
$exams = $course->getEligibility();
$allExamsList = $course->getAllEligibilityExams(false);

if(!(empty($courseStatus) && empty($courseFees) && empty($allExamsList) && empty($questionCount))){
    if(empty($courseStatus)){
        $courseStatus = "-";    
    }
    if(empty($courseFees)){
        $courseFees = "-";
    }
}

?>

<?php

if(!empty($aggregateReviewInfo) && $aggregateReviewInfo['aggregateRating']['averageRating'] > 0){ 


    if($courseReviewCount >= 1){

        $listingId = $institute->getId();
        $type = 'all_content_pages';
        $optionalArgs['typeOfListing'] = $institute->getType();
        $optionalArgs['typeOfPage'] = 'reviews';
        $optionalArgs['courses'] = array($course->getId()); // course ids
        
        //$reviewUrl =  getSeoUrl($listingId,$type,$title="",$optionalArgs,$flagDbhit='NA',$creationDate='');
        $reviewUrl = $institute->getURL()."/reviews";
        
       ?> <i></i>
<?php
  $this->load->view("listingCommon/aggregateReviewInterlinkingWidget", array('allReviewsUrl' => $reviewUrl,'aggregateReviewsData' => $aggregateReviewInfo,'reviewsCount' => $reviewCount , 'isPaid' => $course->isPaid()));
}
    
}
?>
</div>


<!--table sec-->
<div class="m-tab">

<?php
   
    if(!empty($courseStatus)){
            $courseStatusDisplay = (strlen($courseStatus) > 36) ? substr($courseStatus, 0, 36).'...' : $courseStatus;
            if($courseStatusDisplay == $courseStatus) {
                $title = '';
            } else {
                $title = "title='".$courseStatus."'";
            } ?>
        
            <div class="tuple-alum-col">
                <p class="fee">Course Status</p>
                <strong class="inr" <?php echo $title;?>>
                <?=$courseStatusDisplay;?>   
                </strong>
            </div>
        <?php
    }
    ?>

<?php
    if(!empty($courseFees)){

        if($courseFees != "-"){
            $courseFees = getRupeesDisplableAmount($courseFees);    
        }
        
        ?>
        <div class="tuple-fee-col">
            <p class="fee">Total Fees <?php  echo ($courseFeesUnit) ?  '('.$courseFeesUnit.')' :  ''; ?></p>
            <strong class="inr">
            <?php echo $courseFees;?>
            <?php if($courseFees != "-" && !empty($feesObj)){
                ?>
                <i class="fc_icons ic_icon_fee" onmouseover="showHTMLBlockByClass('srpHoverCntntFees_<?=$course->getId();?>');" onmouseout="hideHTMLBlockByClass('srpHoverCntntFees_<?=$course->getId();?>');"></i>

                <div style="display: none;" id="srpHoverCntntFees_<?=$course->getId();?>" class="srpHoverCntnt srpHoverCntntFees_<?=$course->getId();?>"><p>The shown fees is for the general category and will vary for other reservation categories.</p></div>
                <?php
            }
            ?>             
             </strong>
        </div>
            
        <?php
    }
?>
<?php
 
 if(!empty($exams['general'])){
    $marksFormat = array(
              'percentile' => "%ile",
              'percentage' => "%",
              'score/marks' => "marks",
              'rank'        =>"rank",
              'CGPA' => 'cgpa'
          );    
    $examArr = array();
    foreach ($exams['general'] as $exam) {
        $examId = $exam->getExamId();
        $examName = $exam->getExamName();
        $examMarks = $exam->getValue();
        $examUnit = $marksFormat[$exam->getUnit()];
        $examDisplayName = '';
        if($examMarks > 0) {
            $examDisplayName = $examName.' ('.$examMarks.' '.$examUnit.')';
        } else {
            $examDisplayName = $examName;
        }
        $examUrl = '';
        if(!empty($eligibilityUrls[$examId])){
            $examUrl = $eligibilityUrls[$examId];
        }
        $examArr[] = array('name'=>$examDisplayName,'url'=>$examUrl);
        if(!empty($examId)){
            unset($allExamsList[$examId]);
        }else{
            unset($allExamsList[$examName]);
        }
        
    }
 }

 foreach ($allExamsList as $examId=>$examName) {
    $examUrl = '';
    if(!empty($eligibilityUrls[$examId])){
        $examUrl = $eligibilityUrls[$examId];
    }
    $examArr[] = array('name'=>$examName,'url'=>$examUrl);
 }
 
 if(!empty($examArr)){
    ?>
        <div class="tuple-exam-dtls">
            <p class="fee">Exams Required (Cut-off)</p>
            <strong class="inr">
                <?php if(!empty($examArr[0]['url'])){echo '<a target="_blank" '.(empty($ga_exam['attr'])?'':' ga-attr="'.$ga_exam['attr'].'"').(empty($ga_exam['optlabel'])?'':' ga-optlabel="'.$ga_exam['optlabel'].'"').(empty($ga_exam['page'])?'':' ga-page="'.$ga_exam['page'].'"').' href='.($examArr[0]['url']).'>';} ?>
                    <?php echo $examArr[0]['name']; ?>
                <?php if(!empty($examArr[0]['url'])){ echo '</a>'; }?>
            </strong>
            
            <?php 
                if(count($examArr) >= 1) { 
                   array_shift($examArr);
                }
            
            if(!empty($examArr)){
                ?>
                <span class="more-exam">+ <?php echo count($examArr); ?> more
                    <div class="srpHoverCntnt">
                        <?php foreach ($examArr as $examStr) { ?>
                            <p>
                                <?php if(!empty($examStr['url'])){echo '<a target="_blank" '.(empty($ga_exam['attr'])?'':' ga-attr="'.$ga_exam['attr'].'"').(empty($ga_exam['optlabel'])?'':'ga-optlabel="'.$ga_exam['optlabel'].'"').(empty($ga_exam['page'])?'':' ga-page="'.$ga_exam['page'].'"').' href='.($examStr['url']).'>';} ?>
                                    <?php echo $examStr['name']; ?>
                                <?php if(!empty($examStr['url'])){ echo '</a>'; }?>
                            </p>
                        <?php } ?>
                    </div>
                </span>
                <?php
            }
            ?>
        </div>

    <?php    
 }
?>


</div>
<?php
if($questionCount >= 1){
    ?>
    <div class="tuple-revw-sec">
    
    <?php
    if($questionCount >= 1){

        $listingId = $institute->getId();
        $type = 'all_content_pages';
        $optionalArgs['typeOfListing'] = $institute->getType();
        $optionalArgs['typeOfPage'] = 'questions';
        $optionalArgs['courses'] = array($course->getId()); // course ids
        
        //$questionsUrl =  getSeoUrl($listingId,$type,$title="",$optionalArgs,$flagDbhit='NA',$creationDate='');
        $questionsUrl = $institute->getURL()."/questions";
        
        if(DO_SEARCHPAGE_TRACKING && DO_TUPLE_TRACKING && in_array($product,array('AllCoursesPage','Category','SearchV2')) && !empty($trackingSearchId)){
            $tupleQuestionCount = empty($tupleCourseCount) ? $tuplenumber : $tuplenumber.'|'.$tupleCourseCount;
            $trackingstring = "{$TupleClickParams['from']}=serp&{$TupleClickParams['pagenum']}={$pageNumber}&{$TupleClickParams['tuplenum']}={$tupleQuestionCount}&{$TupleClickParams['clicktype']}=questions&{$TupleClickParams['listingtypeid']}={$course->getId()}";
            $trackingstring .= "&{$TupleClickParams['trackingSearchId']}={$trackingSearchId}";
            if(!empty($trackingFilterId)){
                $trackingstring .= "&{$TupleClickParams['trackingFilterId']}=".$trackingFilterId;
            }
        }
        ?>
            <span>
                <b><?php echo tupleReviewQuesCount($questionCount);?></b>
                <a type="questions" target="_blank" href="<?php echo add_query_params($questionsUrl,$trackingstring); ?>"><?=$questionText;?>
                    <?php 
                    if(in_array($product,array('AllCoursesPage','Category','SearchV2'))){
                        ?>
                        <div style="display: none;" class="srpHoverCntnt"><p><?php echo $websiteTourContentMapping['Questions'] ?></p></div>
                        <?php
                    }
                    ?>
                </a>
        </span>
        <?php
    }
    ?>         
</div>
    <?php
}
?>

<!--ff-->
