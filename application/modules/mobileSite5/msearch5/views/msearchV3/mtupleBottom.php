<?php 
$displayLocation = $displayDataObject[$course->getId()]->getDisplayLocation();
$courseInfoString = $displayDataObject[$course->getId()]->getCourseInfoString();
$courseFeesArr = $displayDataObject[$course->getId()]->getFees();
$courseFeesUnit = $courseFeesArr['feesUnit'];
$courseFees = $courseFeesArr['fees'];
$affiliationStr = "";
if(!empty($courseStatusData[$course->getId()])){
    $affiliationStr = $courseStatusData[$course->getId()];
}

$locationSuffixForUrl = "";
if(!empty($displayLocation)){
    $locationSuffixForUrl = $displayDataObject[$course->getId()]->getLocationSuffixForUrl();
}
//get reviews
$courseReviewCount = $course->getReviewCount();
$showExams = $displayDataObject[$course->getId()]->getIsShowExams();
//get approvals
$approvalArr = $course->getRecognition();
foreach ($approvalArr as $key => $approval) {
    if($approval->getId() == NBA_APPROVAL) continue;
    $approvals[] = strtoupper($approval->getName());
}

if($showExams) { //get exams
    $exams = $course->getEligibility();
    $allExamsList = $course->getAllEligibilityExams();
    $examMoreStr = "";
    $marksFormat = array(
                  'percentile' => "%ile",
                  'percentage' => "%",
                  'score/marks' => "marks",
                  'rank'        =>"rank",
                  'CGPA' => 'cgpa'
            );
    $examArr = array();
    foreach ($exams['general'] as $exam) {
        $examName = $exam->getExamName();
        $examUnit = $exam->getUnit();
        $examMarks = $exam->getValue();
        $examId = $exam->getExamId();
        if($examMarks > 0) {
            $examMarksName = ' ('.$examMarks.' '.$examUnit.')';
        } else {
            $examMarksName = '';
        }
        $examUrl = '';
        if(!empty($eligibilityUrls[$examId])){
            $examUrl = $eligibilityUrls[$examId];
        }
        $examArr[] = array('exammarks'=>$examMarksName,'url'=>$examUrl,'name'=>$examName);
        if(!empty($examId)){
            unset($allExamsList[$examId]);
        }else{
            unset($allExamsList[$examName]);
        }
    }

    foreach ($allExamsList as $examId=>$examName) {
        $examUrl = '';
        if(!empty($eligibilityUrls[$examId])){
            $examUrl = $eligibilityUrls[$examId];
        }
        $examArr[] = array('url'=>$examUrl,'name'=>$examName);
    }

    if(count($examArr) > 1){
        $examMoreStr = '+ '.(count($examArr)-1).' More';
    }
    
    if(!empty($examArr)){
        $examName = $examArr[0]['name'];
        $examMarksString = $examArr[0]['exammarks'];
        $examUrl = $examArr[0]['url'];
    }else{
        $examName = "-";
    }

 }

$courseShortString = (strlen($course->getName()) > 55) ? substr($course->getName(), 0, 52).'..' : $course->getName();

if(DO_SEARCHPAGE_TRACKING && DO_TUPLE_TRACKING && in_array($product,array('MAllCoursesPage','McategoryList','MsearchV3')) && !empty($trackingSearchId)){
    global $TupleClickParams;
    $tupleReviewCount = empty($tupleCourseCount) ? $tuplenumber : $tuplenumber.'|'.$tupleCourseCount;
    $trackingstring  = "{$TupleClickParams['from']}=serp&{$TupleClickParams['pagenum']}={$pageNumber}&{$TupleClickParams['tuplenum']}=$tupleReviewCount&{$TupleClickParams['clicktype']}=course&{$TupleClickParams['listingtypeid']}={$course->getId()}";
    $trackingstring .= "&{$TupleClickParams['trackingSearchId']}={$trackingSearchId}";
    if(!empty($trackingFilterId)){
        $trackingstring .= "&{$TupleClickParams['trackingFilterId']}=".$trackingFilterId;
    }
}
?>
<div class="srp-col">
    <div class="srp-clg-name <?php echo ($courseReviewCount < 1) ? 'srp-clg-maxWidth': ''; ?> flLt">
        <a href="<?php echo add_query_params($course->getURL().$locationSuffixForUrl,$trackingstring); ?>" class="subCourse-link" id="courseName_<?php echo $course->getId();?>" value="<?php echo base64_encode(json_encode(html_escape($course->getName()))); ?>"><?php echo html_escape($courseShortString); ?></a>
    </div>
    <div class="flRt">
        <?php
        $anchorText =' reviews'; 
        if($courseReviewCount == 1) {
        $anchorText =' review'; 
        }
        if($courseReviewCount >= 1 && !(empty($courseWidgetData))) {
            $listingId = $institute->getId();
            $type = 'all_content_pages';
            $optionalArgs['typeOfListing'] = $institute->getType();
            $optionalArgs['typeOfPage'] = 'reviews';
            $optionalArgs['courses'] = array($course->getId()); // course ids
            $reviewUrl =  getSeoUrl($listingId,$type,$title="",$optionalArgs,$flagDbhit='NA',$creationDate='');
            $showAggregateRating = true;
            $aggregateReviewsData = $courseWidgetData['aggregateRating']['averageRating'];
            if(empty($aggregateReviewsData) && $aggregateReviewsData<=0){
               $showAggregateRating = false;     
            }
            if($course->isPaid() && $aggregateReviewsData<3.5){
               $showAggregateRating = false;     
            }
            if(DO_SEARCHPAGE_TRACKING && DO_TUPLE_TRACKING && in_array($product,array('MAllCoursesPage','McategoryList','MsearchV3')) && !empty($trackingSearchId)){
                $tupleReviewCount = empty($tupleCourseCount) ? $tuplenumber : $tuplenumber.'|'.$tupleCourseCount;
                $trackingstring = "{$TupleClickParams['from']}=serp&{$TupleClickParams['pagenum']}={$pageNumber}&{$TupleClickParams['tuplenum']}={$tupleReviewCount}&{$TupleClickParams['clicktype']}=reviews&{$TupleClickParams['listingtypeid']}={$course->getId()}";
                $trackingstring .= "&{$TupleClickParams['trackingSearchId']}={$trackingSearchId}";
                if(!empty($trackingFilterId)){
                    $trackingstring .= "&{$TupleClickParams['trackingFilterId']}=".$trackingFilterId;
                }
            } 
            
            ?>
            <a href="<?php echo add_query_params($reviewUrl,$trackingstring); ?>" class="review-col reviewsButton"><?php if($showAggregateRating){ ?><i class="review-Icn"><span class="review-count"><?php echo $aggregateReviewsData ?></span>/5</i><?php }?><?=formatAmountToNationalFormat($courseReviewCount,1,array('k','l', 'c'),'count')?><?php echo $anchorText?></a><?php  } ?>

        <?php $this->load->view('msearch5/msearchV3/mtupleCompare'); ?>
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

<?php
$hideTuple = false;
if($showExams && $courseFees == "-" && empty($examArr)){
    $hideTuple = true;
}
?>
<?php if(!$hideTuple || in_array($product, array('McategoryList', 'MsearchV3', 'MAllCoursesPage', 'brochureRecoLayer'))) { ?>
    <div class="srp-col srp-col-brdr">
        <?php if(!$hideTuple) { ?>
            <div class="cm-div">
                <div class="srp-sub-col" style="width:30%">
                    <label>Total fees <?php echo ($courseFees == '-')?'':'(&#8377;)';?></label>
                    <p><?php echo $courseFees ?></p>
                </div>
                <div class="srp-sub-col" style="width:70%;">
                    <?php if($showExams){ ?>
                        <label>Exams (Cut-off)</label>
                        <p><a <?php 
                            if(!empty($examUrl)){
                                echo (empty($ga_exam['attr'])?'':' ga-attr="'.$ga_exam['attr'].'"').(empty($ga_exam['optlabel'])?'':' ga-optlabel="'.$ga_exam['optlabel'].'"').(empty($ga_exam['page'])?'':' ga-page="'.$ga_exam['page']).'" href="'.$examUrl.'" ';
                            }
                            else{
                                echo ' href="javascript:void(0);" ';
                            }
                            ?> class="srpMore-link"><?php echo $examName; echo $examMarksString; ?>  </a><span class="font-11"> <?php echo $examMoreStr; ?></span></p>
                    <?php }
                    else { ?>
                        <label>Mode Of Study</label>
                        <p><?php echo $courseInfoString ?></p>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
        <?php if(in_array($product, array('McategoryList', 'MsearchV3', 'MAllCoursesPage', 'brochureRecoLayer'))) { ?>
            <div class="new-tab">
                <div class="srp-sub-col">
                    <?php if(in_array($course->getId(), $shortlistedCourses)) {
                        $shortlistText = 'Shortlisted';
                    } else {
                        $shortlistText = 'Shortlist';
                    }
                    switch ($product) {
                        case 'McategoryList':
                            $pageType = 'NM_Category';
                            break;
                        
                        case 'MsearchV3':
                            $pageType = 'NM_SERP';
                            break;

                        case 'MAllCoursesPage':
                            $pageType = 'NM_ALL_COURSES';
                            break;

                        case 'brochureRecoLayer':
                            $pageType = 'EBrochure_RECO_Layer';

                        default:
                            //$pageType = $product;
                            break;
                    } ?>
                    <a 
                        href="javascript:void(0);" 
                        pagenum='<?php echo $pageNumber; ?>' 
                        product="<?php echo $product; ?>" 
                        track="on" 
                        id="shrt_<?php echo $course->getId() ?>" 
                        class="shortlist-site-tour noredirect btn-mob-blue shortlist tupleShortlistButton shrt_<?php echo $course->getId(); ?>"
                        data-pagetype="<?php echo $pageType ?>" 
                        attr-val="<?php echo $course->getId() ?>" 
                        data-instid='<?php echo $institute->getId();?>' 
                        data-trackid="<?=$trackingIds['shortlist'];?>"
                    >
                        <?php echo $shortlistText ?>
                    </a>
                </div>
                <div class="srp-sub-col">
                    <?php
                        if(empty($brochureText)) {
                            $brochureText = 'Request Brochure';
                        }
                        $disable = false;
                        if(in_array($course->getId(), $brochuresMailed)){
                            $disable = true;
                        }
                    ?>
                    <a 
                        id="brchr_<?php echo $course->getId(); ?>" 
                        pagenum='<?php echo $pageNumber; ?>' 
                        product="<?php echo $product; ?>" 
                        track="on" 
                        data-instid='<?php echo $institute->getId();?>' 
                        class="deb-site-tour noredirect btn-mob<?php echo $disable?"-dis":""; ?> brochure <?php echo $disable?"":"tupleBrochureButton" ; ?> brchr_<?php echo $course->getId(); ?>" 
                        attr-val="<?php echo $course->getId(); ?>" 
                        data-trackid="<?=$trackingIds['downloadBrochure'];?>" 
                        data-pagetype="<?=$product;?>"
                        href = "javascript:void(0);"
                        gaText="<?php echo 'AB_'.$brochureText;?>"
                        cta-type="<?php echo CTA_TYPE_EBROCHURE;?>"
                    >
                        <?php echo $disable ? "Brochure Mailed" : $brochureText; ?>
                    </a>
                </div>
            </div>
        <?php } ?>
    </div>
<?php } ?>
<p class="clr"></p>
</div>