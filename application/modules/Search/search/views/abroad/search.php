<?php
	
	$title = 'Search Education information – Shiksha.com';
	$metaTitle = 'Search Education information – Shiksha.com';
	$metDescription = 'Search for all educational and career related information includes latest educational news, articles, exams, colleges, university, institute, courses, notifications, exam dates, and more';
	//$metaKeywords 	= 'Shiksha, Study, study abroad, foreign education, foreign education, GMAT, graphic designing, education, career, career options, career prospects, engineering, mba, medical, mbbs, study abroad, foreign education, college, university, institute, courses, coaching, technical education, higher education, forum, community, education career experts, ask experts, admissions, results, events, scholarships -'.htmlspecialchars($keyword);
	$headerComponents = array (
		'css'			=> array('studyAbroadSearch', 'studyAbroadCommon'),
		'js'			=> array(),
		'taburl' 		=>  site_url(),
		'title'			=>	$title,
		'canonicalURL' 	=> "",
		'metaDescription' 			=> $metDescription,
		'metaKeywords'				=> $metaKeywords,
		'pgType'	        => 'searchPage',
		'pageIdentifier'    => $beaconTrackData['pageIdentifier']
	);
	$this->load->view('common/studyAbroadHeader', $headerComponents);
	echo jsb9recordServerTime('SA_SEARCH_PAGE',1);
?>
<script>
	var showCompareLayerPage = true;
</script>
<?php
	$this->load->view('abroad/search_page_content');
	$this->load->view('abroad/footer', $headerComponents);
?>