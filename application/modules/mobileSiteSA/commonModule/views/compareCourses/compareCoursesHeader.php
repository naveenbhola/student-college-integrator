<?php
$title ='';
$metaDescription ='';

$courseIds = array_keys($courseDataObjs);
//prepare title and meta description
switch ($coursesCount) {
	case 1:
			$title = 'Compare '.$courseDataObjs[$courseIds[0]]->getName();
			$metaDescription = $courseDataObjs[$courseIds[0]]->getName().'. Comparison - fees, ranking, eligibility, admission process, and more.';
		break;
	case 2:
			$title = 'Compare '.$courseDataObjs[$courseIds[0]]->getName()." and ".$courseDataObjs[$courseIds[1]]->getName();
			$metaDescription = $courseDataObjs[$courseIds[0]]->getName()." and ".$courseDataObjs[$courseIds[1]]->getName().'. Comparison - fees, ranking, eligibility, admission process, and more.';
		break;
}

$headerComponents = array(
    'cssBundleMobile' => 'sa-compare-course-mobile',
    'title'   		=> $title,
    'metaDescription' =>  $metaDescription,
    'metaKeywords'    => '',
    'firstFoldCssPath'    => 'commonModule/compareCourses/css/compareCoursesFirstFoldCss',
    'deferCSS' => true
);

$this->load->view('commonModule/headerV2',$headerComponents); ?>
<div class="header-unfixed">
<div class="layer-header">
    <img id='beacon_img' src="<?php echo IMGURL_SECURE; ?>/public/images/blankImg.gif" width=1 height=1 >
    <a class="back-box" href="<?php echo $referrerPageURL; ?>" style="vertical-align:middle"><i class="sprite back-icn"></i></a>            
    <p style="text-align:center"><span>COMPARE COLLEGES</span>
    </p>
</div>
</div>
