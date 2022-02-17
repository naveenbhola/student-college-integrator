<?php
$defaultJs = array('common','customCityList','processForm','ajax-api','national_listings','caCoursePage','discussion','ana_common','CAValidations');
if($js){
	$defaultJs = array_merge($defaultJs,$js);
}

$url = $course->getURL();
$currentURL = explode("?", $url);
$canonicalurl = $currentURL[0];

$noIndexMetaTag = false;

$headerComponents = array(
	'js'=>$defaultJs,
	'jsFooter'=>array('onlinetooltip'),
	'css'=>array('nationalCourses','recommend'),
	'product'=>'nationallistings',
	'bannerProperties' => array('pageId'=>'CATEGORY', 'pageZone'=>$categoryData['page'] .'_TOP', 'shikshaCriteria' => $criteriaArray),
	'canonicalURL' => $canonicalurl,
	'page_is_listing' => 'YES',
	'title' => $title,
	'metaDescription' => $metaDescription,
	'noIndexMetaTag' => $noIndexMetaTag
);

if($inlineView) {
	$this->load->view('common/headerWithoutHTML', $headerComponents);
}
else {
	$this->load->view('common/header', $headerComponents);
}

/*
 * Subcategory Course pages related changes..
 */

if(empty($course_page_required_category)) {
	$course_page_required_category = 0;
}

$cpgs_backLinkArray = array();	
$coursepage_sub_cat_array = $googleRemarketingParams['subcategoryId'];		
foreach ($coursepage_sub_cat_array as $coursepage_subcat) {
	if(checkIfCourseTabRequired($coursepage_subcat)){
		$course_page_required_category = $coursepage_subcat;
		break;
	}
}	
$cpgs_backLinkArray = array("MESSAGE" => "View all institutes and courses", "LANDING_URL" => $breadCrumb[0]['url']);

$x = explode('.', $course_reb_url);
$extension = strtolower(end($x));
if($extension == "pdf") {
	echo '<script>var brochureType = "pdf";</script>';
} else {
	echo '<script>var brochureType = "others";</script>';
}	

if(!$course->isPaid()){
	echo '<script>var freeCourseIdOfListingPage = '.$course->getId().';</script>';
}

$load_registration = "false";
if(in_array("find_best_institute", $cta_widget_list)) {
	$load_registration = "true";
}

$campus_connect_available = "false";
if(in_array("campus_connect", $cta_widget_list)) {
	$campus_connect_available = "true";
}

echo jsb9recordServerTime('SHIKSHA_NATIONAL_COURSE_DETAIL_PAGE',1);

?>
<script>
var courseIdForTracking = <?php echo $course->getId(); ?>;
var instituteIdForTracking = <?php echo $institute->getId(); ?>;
</script>

<script>
	var freeCoursesHavingBrochure = new Array(<?=implode(",",$freeCoursesHavingIds)?>);
	var brochurePDFExt = new Array(<?=implode(",",$brochurePDFExtArray)?>);	
	var isBrochureExistsForCourseArr = new Array(<?=implode(",",array_merge($isBrochureExistsForCourse,array(0,-1)))?>);
	var isCourseDropdownEnabled = <?=($showBrochureListOnCoursePageFlag ? 1 : 0)?>
	
	var load_registration = "<?php echo $load_registration;?>";
	var campus_connect_available = "<?php echo $campus_connect_available;?>";
	var national_listings_obj = new national_listings();
	var universal_page_type = "<?php echo $pageType;?>";
	if(universal_page_type == 'institute') { 
		universal_page_type = 'Insti';
	} else if(universal_page_type == 'course') {
		universal_page_type = 'Course';
	} 
	var universal_page_type_id = "<?php echo $typeId;?>";
	
	if(document.getElementById('cpgs_param_id')) {	
		document.getElementById('cpgs_param_id').value = '<?php echo $course_page_required_category?>'+"_Institutes";
		document.getElementById('cpgs_param_id1').value = '<?php echo $course_page_required_category?>'+"_Institutes";
	}

	var national_listings_obj = new national_listings();
	var course_id = '<?php echo $course->getId();?>';
</script>
<?php
	$this->load->view('national/jumpToAnchors');
?>

<div id="content-child-wrap">
<div id="management-wrapper" itemscope itemtype="http://schema.org/WebPage">
                <!--Course Content starts here-->
                <div id="management-content-wrap">
			<!-- BREADCRUMB WIDGET STARTS	-->
			<?php $this->load->view('national/widgets/breadcrumb');?>
			<!-- BREADCRUMB WIDGET ENDS	-->
			<?php
			/*if($course_page_required_category>0) {
				$cpgs_backLinkArray['AUTOSCROLL'] = 1;
				 echo '<div style="padding:0px;">';
					echo Modules::run('coursepages/CoursePage/loadCoursePageTabsHeader', $course_page_required_category, "Institutes", FALSE,$cpgs_backLinkArray,FALSE);
				 echo '</div>';
			}*/
			?>