<?php
if($pageType == 'category') {
    $courseUrl = '/getCategoryPage/colleges/'. $categoryUrlName .'/All/All/All/course';
    $instituteUrl = '/getCategoryPage/colleges/'. $categoryUrlName;
}
if($pageType == 'country') {
    $instituteUrl = '/getCategoryPage/colleges/studyAbroad/'. $countryName ;
    $courseUrl = '/getCategoryPage/colleges/studyAbroad/'. $countryName .'/All/All/course' ;
}
if($pageType == 'exam') {
    $instituteUrl = '/shiksha/testprep/'.$examId. '/'.$examName;
    $courseUrl = '/shiksha/testprep/'.$examId. '/'.$examName .'/course';
}


foreach($productsCount as $product => $count){
    $$product = $count;
}
$criteriaStr = '';
$countryId = isset($criteria['countryId'])? $criteria['countryId'] : 1;
$categoryId = isset($criteria['categoryId'])? $criteria['categoryId'] : 1;
foreach($criteria as $criteriaName => $criteriaValue) {
    $criteriaStr .= $criteriaName .'='.$criteriaValue .'&';
}
$criteriaStr .= 'c='. rand();
if(isset($totalArticleCount[$resultKey])) {
    if($totalArticleCount[$resultKey] > 1) $totalArticles =  $totalArticleCount[$resultKey] .' Articles';
    else if($totalArticleCount[$resultKey] == 1) $totalArticles = '1 Article';
    else $totalArticles = 'No Article';
}else {
$totalArticles = 'No Article';
}

$totalQnAs =  (!isset($totalQnACount[$resultKey]) || $totalQnACount[$resultKey] < 1) ? 'No ' : $totalQnACount[$resultKey] ;
$totalInstitutes=  (!isset($totalInstituteCount[$resultKey] ) || $totalInstituteCount[$resultKey] < 1) ? 'No Institutes ' : ($totalInstituteCount[$resultKey] ==1 ? '1 Institute' : $totalInstituteCount[$resultKey] .' Institutes') ;
$totalCourses= (!isset($totalCourseCount[$resultKey]) ||  $totalCourseCount[$resultKey] < 1) ? 'No Courses' : ($totalCourseCount[$resultKey] ==1 ? '1 Course' : $totalCourseCount[$resultKey] .' Courses') ;
$totalEvents=  (!isset($totalEventCount[$resultKey]) || $totalEventCount[$resultKey]) < 1 ? 'No Important Dates' :( $totalEventCount[$resultKey] ==1 ? '1 Important Date' : $totalEventCount[$resultKey] .' Important Dates' );
$totalScholarships=  ( !isset($totalScholarshipCount) || $totalScholarshipCount[$resultKey] < 1 ) ? 'No Scholarships' : ($totalScholarshipCount[$resultKey] ==1 ? '1 Scholarship' : $totalScholarshipCount[$resultKey] .' Scholarships' );
?>
<div class="lineSpace_15" style="margin-left:8px">
							<div class="float_L w160 careerArticles bld fontSize_12p"><a href="/blogs/shikshaBlog/showArticlesList?<?php echo $criteriaStr; ?>" title="" class="fontSize_12p bld" id = "articleStat"><?php echo $totalArticles; ?></a></div>
                            <div class="float_L w160 careerQuestion bld fontSize_12p"><a href="<?php echo '/messageBoard/MsgBoard/discussionHome/'.$categoryId.'/1/'.$countryId;?> " title="" class="fontSize_12p bld" id = "questionStat"><?php echo $totalQnAs; ?> Q &amp; A</a></div>
							<!--
                            <div class="float_L w160 careerInsititute bld fontSize_12p"><a href="<?php echo $instituteUrl?>" title="" class="fontSize_12p bld" id = "instituteStat"><?php echo $totalInstitutes; ?></a></div>-->
                            <?php 
                            if($pageType == 'exam') {
                                $displayScholarships = 'none';
                            }
                            ?>
                            <!--<div class="float_L w160 careerCourses bld fontSize_12p" style="display:<?php echo 'none';; ?>"><a href="<?php echo $courseUrl?>" title="" class="fontSize_12p bld" id = "courseStat"><?php echo $totalCourses; ?></a></div>-->
							<div class="float_L w160 careerScholarship bld fontSize_12p" style="display:<?php echo $displayScholarships; ?>"><a href="/listing/Listing/showScholarshipsList?<?php echo $criteriaStr; ?>" title="" class="fontSize_12p bld" id = "scholStat"><?php echo $totalScholarships; ?></a></div>
                            <div class="float_L w160 careerDates bld fontSize_12p"><a href="<?php echo "/events/Events/index/1/".$countryId."/".$categoryId ?>" title="" class="fontSize_12p bld" id = "eventStat"><?php echo $totalEventCount[$resultKey]; ?> Important Dates</a></div>
							<div class="clear_L"></div>
						</div>

