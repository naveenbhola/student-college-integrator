<?php
$instituteDetails 				= $brochure_related_data['institute_details'];
$courseDetails 					= $brochure_related_data['course_details'];
$studyAbroadCourses 			= $brochure_related_data['study_abroad_courses'];
$coursesWithMultipleLocation 	= $brochure_related_data['multiple_location_courses'];

if(count($featuredInstituteDetailsForDownloadEbrochue['institute']) >= 1){	
$instituteDetails =  $instituteDetails + $featuredInstituteDetailsForDownloadEbrochue['institute'];
}
if(count($featuredInstituteDetailsForDownloadEbrochue['course']) >= 1){
$courseDetails = $courseDetails + $featuredInstituteDetailsForDownloadEbrochue['course'];
}

$encounteredInstituteIds = array();
?>
<div id="institute_name_container">
	<?php
	foreach($instituteDetails as $institute){
		if(!in_array($institute['id'], $encounteredInstituteIds)){
		?>
			<div id="institute<?php echo trim($institute['id']);?>name" style="display:none;">
				<?php echo strip_tags(trim($institute['name'] .", " . trim($institute['city_name'])));?>
			</div>
		<?php
		$encounteredInstituteIds[] = $institute['id'];
		}
	}
	?>
</div>
<div id="course_dd_container">
	<?php
	$encounteredIds = array();
	foreach($courseDetails as $instituteId => $courses){
		?>
		<select id="applynow<?php echo $instituteId;?>" style="display:none;">
		<?php
		foreach($courses as $course){
			if(!in_array($course['id'], $encounteredIds)){
			?>
				<option title="<?php echo trim($course['name']);?>" value="<?php echo trim($course['id']); ?>">
					<?php echo $course['name'];?>
				</option>
			<?php
			$encounteredIds[] = $course['id'];
			}
		}
		?>
		</select>
		<?php
	}
	?>
</div>

<?php
$bannerURL = "";
$bannerLandingURL = "";
if(!empty($banner_details)){
	$bannerURL = $banner_details['file_path'];
	$bannerLandingURL = $banner_details['landing_url'];
}

$paramRankingPageId 		= $ranking_page->getId();
$paramRankingPageName 		= $ranking_page->getName();
$paramRankingPageSubCatId 	= $ranking_page->getSubCategoryId();
$paramRankingPageCatId 		= $ranking_page->getCategoryId();
$paramRankingPageSpecializationId = $ranking_page->getSpecializationId();
?>
<script type="text/javascript">
	var run_flag_article = false;
	var listings_with_localities = <?php echo $listings_with_localities; ?>;
	var $categorypage = {};
	var $rankingPage = {};
	$rankingPage.rankingPageBannerURL = "<?php echo $bannerURL;?>";
	$rankingPage.rankingPageBannerLandingURL = "<?php echo $bannerLandingURL;?>";
	$rankingPage.studyAbroadIds = <?php echo json_encode($studyAbroadCourses);?>;
	$rankingPage.instituteWithMultipleCourseLocations = <?php echo json_encode($coursesWithMultipleLocation);?>;
	$rankingPage.registrationSource 		= "RANKING_PAGE_REQUEST_EBROCHURE";
	$rankingPage.rankingPageKey 			= "RANKING_PAGE";
	$rankingPage.requestBrochureActionType 	= "RANKING_PAGE_REQUEST_EBROCHURE";
	$rankingPage.pageId = "<?php echo $paramRankingPageId;?>";
	$rankingPage.pageName = "<?php echo $paramRankingPageName;?>";
	$rankingPage.subCategoryId = "<?php echo $paramRankingPageSubCatId;?>";
	$rankingPage.categoryId = "<?php echo $paramRankingPageCatId;?>";
	$rankingPage.specializationId = "<?php echo $paramRankingPageSpecializationId;?>";
	
	localityArray = <?=json_encode($GLOBALS['localityArray'])?>;
	for(var key in localityArray){
		custom_localities[key] = localityArray[key];
	}
</script>