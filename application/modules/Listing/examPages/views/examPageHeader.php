<?php
$duplicateShikshaCriteria = array('MAT'=>'SHK_EXAM1','CUSAT_CAT'=>'SHK_CUSAT','PESSAT_MBA'=>'SHK_PST_MBA');
$headerComponents = array (
		'js' => array (
				'multipleapply',
				'user' ,
                'ajax-api'
		),
		'jsFooter' => array (
				'common',
				'processForm' 
		),
		'bannerProperties' => array(
				'pageId'=>'EXAM',
				'pageZone'=>'TOP',
				'examPageShikshaCriteria' => (array_key_exists($shikshaCriteria, $duplicateShikshaCriteria)) ? $duplicateShikshaCriteria[$shikshaCriteria] : $shikshaCriteria
			),
		'product' => "examPage" ,
		'title'						=>	$titleText,
		'metaDescription' => $metaDescription,
		'canonicalURL' 		=> $canonicalurl
);

$this->load->view ( 'common/header', $headerComponents );
echo jsb9recordServerTime('NATIONAL_EXAM_PAGES', 1);
?>
<script type="text/javascript">
window.regFormLoad = [];
</script>
<?php 


?>
<!--  div Id top-nav is used to make common.js  code workable without GNB Layer. this id is referance for making home page navigation bar sticky   -->
<div id="top-nav" style="visibility:hidden;height:0px"></div>
<div id="exam-page-TopNav" style ="padding:0 10px">

<?php
global $isNewExamPage;
$isNewExamPage = true;
//echo Modules::run ( 'coursepages/CoursePage/loadCoursePageTabsHeader', $subcatId, "Exams", TRUE, array(), FALSE, FALSE, array('exampageExamNameLabel'=>$examName) );
?>

</div>

		