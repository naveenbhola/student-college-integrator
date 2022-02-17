<?php
$headerComponents = array (
		'js' => array (
				'multipleapply',
				'user' ,
                'ana_common',
                'ajax-api',
                'AutoSuggestor',
                'quesDiscPosting'
		),
		'jsFooter' => array (
				'common',
				'processForm' ,
				'myShortlist'
		),
/*		'bannerProperties' => array(
				'pageId'=>
				'pageZone'=>
				'ShikshaCriteria' => $shikshaCriteria
			),
*/			
		'product' => "myShortlist" ,
		'title'						=>	$m_meta_title,
		'metaDescription' => $m_meta_description,
		'canonicalURL' 		=> $canonicalURL
);

$this->load->view ( 'common/header', $headerComponents );
echo jsb9recordServerTime('NATIONAL_MYSHORTLIST', 1);
?>

<?php 


?>
<!--  div Id top-nav is used to make common.js  code workable without GNB Layer. this id is referance for making home page navigation bar sticky   -->
<div id="top-nav" style="visibility:hidden;height:0px"></div>
<div id="exam-page-TopNav" style ="padding:0 10px">

<?php
global $isNewExamPage;
$isNewExamPage = true;
$subcatId = 23;
//echo Modules::run ( 'coursepages/CoursePage/loadCoursePageTabsHeader', $subcatId, "MyShortlist", TRUE );
//$dataArray['CUSTOMIZED_TABS_BAR'] = array('Home', 'Institutes','AskExperts', 'Rankings', 'Exams','ApplyOnline','MyShortlist'); // Adding My Shortlist
//echo Modules::run('coursepages/CoursePage/loadCoursePageTabsHeader', $subcatId, "MyShortlist", TRUE, $dataArray);
?>

</div>
		<!--Shortlist page Starts-->
		<div class="shortlist-wrap clearFix">
		