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
	case 3:
			$title = 'Colleges Comparison-'.$courseDataObjs[$courseIds[0]]->getName()." vs ".$courseDataObjs[$courseIds[1]]->getName()." vs ".$courseDataObjs[$courseIds[2]]->getName();
			$metaDescription = 'See side by side comparison of'.$courseDataObjs[$courseIds[0]]->getName().", ".$courseDataObjs[$courseIds[1]]->getName().", ".$courseDataObjs[$courseIds[2]]->getName().' for fees, ranking, eligibility, admission process, and more.';
		break;
}


$headerComponents = array(
			    'css'               => array('studyAbroadCommon','studyAbroadCompare'),
			    'title'             => $title,
			    'metaDescription'   => $metaDescription,
			    'metaKeywords'      => '',
			    'pageIdentifier'    => $beaconTrackData['pageIdentifier']
			);
$this->load->view('common/studyAbroadHeader', $headerComponents);
echo jsb9recordServerTime('SA_COMPARE_PAGE', 1);
?>
<script>
	var isComparePage = true;
</script>