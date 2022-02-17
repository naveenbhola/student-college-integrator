<?php 
//get fees
$displayLocation = ($course->getCurrentMainLocation() != null) ? $course->getCurrentMainLocation() : $course->getMainLocation();
$feesObj = $course->getFees($displayLocation->getLocationId());
if(!empty($feesObj) && $feesObj->getValue() && isset($searchPageDataProcessorLib)) {
    $courseFeesUnit = $feesObj->getCurrency();
    $courseFees = $feesObj->getValue();
    if($courseFeesUnit!='INR' && isset($currencyConvertService)){
        $courseFees = $currencyConvertService->convert($feesObj->getValue(),$courseFeesUnit);
    }
    $courseFees = $searchPageDataProcessorLib->convertNumericFeesInWords($courseFees,'INR');
} else {
    $courseFees = '-';
    $courseFeesUnit = '';
}

//get reviews
$courseReviewCount = $courseReviews[$course->getId()];

//get approvals
$approvalArr = $course->getApprovals();
foreach ($approvalArr as $key => $approval) {
    $approvals[] = strtoupper($approval);
}

$affiliations = $course->getAffiliations();
foreach ($affiliations as $key => $affiliation){
    if($affiliation[0] == 'indian') {
        $affiliationStr = $affiliation[1] ? $affiliation[1] : 'Indian University';
        $affiliationStr = 'Affiliated to '.$affiliationStr;
    }
    if($affiliation[0] == 'foreign') {
        $affiliationStr = $affiliation[1] ? $affiliation[1] : 'Foreign University';
        $affiliationStr = 'Affiliated to '.$affiliationStr;
    }
    if($affiliation[0] == 'deemed') {
        $affiliationStr =  'Deemed University';
    }
    if($affiliation[0] == 'autonomous') {
        $affiliationStr =  'Autonomous Program';
    }
    $affiliationStr = (strlen($affiliationStr) > 112) ? substr($affiliationStr, 0, 110).'..' : $affiliationStr;
    break;
}

$showExams = false;
if($openSearch) { //check if course is mapped to 23, 56
    if(in_array(23, $courseWiseSubcatIds[$course->getId()]) || in_array(56, $courseWiseSubcatIds[$course->getId()])) {
        $showExams = true;
    }
    $subcatIdForReviews = $course->getDominantSubcategory()->getId();
} else { //check if page subcat is 23, 56
    if(in_array($subCatId, array(23, 56))) {
        $showExams = true;
    }
    $subcatIdForReviews = $subCatId;
}

if($showExams) { //get exams
    $exams = $course->getEligibilityExams();
    $marksFormat = array(
                  'percentile' => "%tile",
                  'percentage' => "%tage",
                  'total_marks' => "marks",
                  'rank'        =>"rank"
            );
    $examName = '-';
    $examMoreStr = '';
    if(!empty($exams)){
        $examName  = $exams[0]->getAcronym();
        $examMarks = $exams[0]->getMarks();
        if($examMarks > 0){
            $examMarks = ' ('.$examMarks.' '.$marksFormat[$exams[0]->getMarksType()].')';
        }
        else{
            $examMarks = '';
        }
    }
    if(count($exams) > 1){
        $examMoreStr = '+ '.(count($exams)-1).' More';
    }

}

$courseShortString = (strlen($course->getName()) > 55) ? substr($course->getName(), 0, 52).'..' : $course->getName();

$trackingstring = '';
if(DO_SEARCHPAGE_TRACKING && (is_object($request) && $request->getTrackingSearchId())){
    global $TupleClickParams;
    $loadedCourseCount = $tupleCourseCount;
    if(!empty($loadedCourseCount)){
        $loadedCourseCount = $tuplenumber.'|'.$loadedCourseCount;
    }
    else{
        $loadedCourseCount = $tuplenumber;
    }
    $trackingstring = "?{$TupleClickParams['from']}=serp&{$TupleClickParams['pagenum']}={$request->getCurrentPageNum()}&{$TupleClickParams['tuplenum']}=$loadedCourseCount&{$TupleClickParams['clicktype']}=course&{$TupleClickParams['listingtypeid']}={$course->getId()}";
    $trackingstring .= "&{$TupleClickParams['trackingSearchId']}={$request->getTrackingSearchId()}";
    $trackingFilterId = $request->getTrackingFilterId();
    if(!empty($trackingFilterId)){
        $trackingstring .= "&{$TupleClickParams['trackingFilterId']}=".$trackingFilterId;
    }
}
?>
<div class="srp-col">
    <div class="srp-clg-name <?php echo ($courseReviewCount <= 3) ? 'srp-clg-maxWidth': ''; ?> flLt">
        <a href="<?php echo $course->getURL().$trackingstring;?>" class="subCourse-link" id="courseName_<?php echo $course->getId();?>" value="<?php echo base64_encode(json_encode(html_escape($course->getName()))); ?>"><?php echo html_escape($courseShortString); ?></a>
    </div>
    <div class="flRt">
<!--         <?php
        if($courseReviewCount > 3) { ?>
            <a href="#collegeReviewViewDetails" class="review-col reviewsButton" value="<?php echo $institute->getId().'_'.$course->getId();?>" data-transition="none" data-rel="dialog" data-dialog="true" data-inline="true" track="on"><i class="review-Icn"><span class="review-count"><?php echo $courseReviewCount; ?></span></i>reviews</a>
            <input type="hidden" id="reviewSubcatId_<?=$course->getId()?>" value="<?=$subcatIdForReviews?>">
        <?php } ?>
 -->        <?php $this->load->view('msearch5/msearchV2/mobileSearchCompare'); ?>
    </div>
</div>
<div class="tupbtmcont" courseid="<?php echo $course->getId();?>">
<?php 
if(!empty($approvals) || !empty($affiliationStr)){
    ?>
    <div class="srp-col">
        <?php 
        foreach ($approvals as $approval) {
            ?>
            <span class="exam-label"><?php echo $approval; ?><i class="exam-tick"></i></span>
            <?php
        }
        if(!empty($approvals) && !empty($affiliationStr)){
            ?>
            <span class="examInfo-sep">|</span>
            <?php
        }
        if(!empty($affiliationStr)){
            ?>
            <span class="examInfo"><i class="affiliation-Icn"></i><?php echo $affiliationStr; ?></span>
            <?php
        }
        ?>
    </div>
    <?php
}
?>
<div class="srp-col srp-col-brdr">
    <div class="srp-sub-col" style="width:30%">
        <label>Total fees <?php echo ($courseFees == '-')?'':'(&#8377;)';?></label>
        <p><?php echo $courseFees; ?></p>
    </div>
    <div class="srp-sub-col" style="width:70%;">
        <?php 
        if($showExams){
            ?>
            <label>Exams (Cut-off)</label>
            <p><?php echo $examName; ?> <span class="font-11"><?php echo $examMarks; ?> <a href="javascript:void(0);" class="srpMore-link"><?php echo $examMoreStr; ?></a></span></p>
            <?php
        }
        else{
            ?>
            <label>Mode Of Study</label>
            <p><?php echo $course->getCourseType(); ?></p>
            <?php
        }
        ?>
    </div>
</div>
<p class="clr"></p>
</div>