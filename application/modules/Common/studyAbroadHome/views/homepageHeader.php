<?php
$headerComponents = array(
        'cssBundle'         => 'sa-home-page',
        'canonicalURL'      => SHIKSHA_STUDYABROAD_HOME,
        'title'             => 'Study Abroad – Colleges, Courses, Exams, Free Counseling',
        'metaDescription'   => 'Want to study abroad ? Get free expert advice and information on colleges, courses, exams, admission, student visa, and application process to study overseas.',
        'metaKeywords'      => 'study abroad, study overseas, overseas education, higher education in abroad, study abroad programs, study abroad colleges, study abroad courses, International studies',
		'pgType'	        => 'homePage',
		'pageIdentifier'	=> $beaconTrackData['pageIdentifier'],
        'firstFoldCssPath'  => $firstFoldCssPath,	
        'skipCompareCode'   => true,
        'trackingPageKeyId' => 348,
        'deferCSS'          => true
);

$this->load->view('studyAbroadCommon/saHeader', $headerComponents);
?>
