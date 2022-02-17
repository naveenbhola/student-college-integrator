<?php
$currentUrl = $_SERVER['REQUEST_URI'];
$currentUrl = str_replace("'", "", $currentUrl);
?>
<!--<input type="hidden" id="category_unified_id" value="<?php //echo $request->getCategoryId(); ?>" />
<input type="hidden" id="categorypage_unified_thankslayer_identifier" value="" />-->
<?php
$localityId = "";
$zoneId = "";
$cityId = "";
$stateId = "";
$countryId = "";

$localityIdUrlParam = $_REQUEST['locality_id'];
$zoneIdUrlParam 	= $_REQUEST['zone_id'];
$cityIdUrlParam 	= $_REQUEST['city_id'];
$countryIdUrlParam 	= $_REQUEST['country_id'];


if(array_key_exists('course_locality_id', $solr_institute_data['qer_params_value'])){
	$localityId = $solr_institute_data['qer_params_value']['course_locality_id'][0];	
} else if(!empty($localityIdUrlParam)){
	$localityId = $localityIdUrlParam;	
}

if(array_key_exists("course_zone_id", $solr_institute_data['qer_params_value'])){
	$zoneId = $solr_institute_data['qer_params_value']['course_zone_id'][0];
} else if(!empty($zoneIdUrlParam)){
	$zoneId = $zoneIdUrlParam;	
}

if(array_key_exists("course_city_id", $solr_institute_data['qer_params_value'])){
	$cityId = $solr_institute_data['qer_params_value']['course_city_id'][0];
} else if(!empty($cityIdUrlParam)){
	$cityId = $cityIdUrlParam;	
}

if(array_key_exists("course_state_id", $solr_institute_data['qer_params_value'])){
	$stateId = $solr_institute_data['qer_params_value']['course_state_id'][0];
}

if(array_key_exists("course_country_id", $solr_institute_data['qer_params_value'])){
	$countryId = $solr_institute_data['qer_params_value']['course_country_id'][0];
} else if(!empty($countryIdUrlParam)){
	$countryId = $countryIdUrlParam;	
}
?>

<script>
var locString = "<?php echo $localityId;?>" + "|" + "<?php echo $zoneId;?>" + "|" + "<?php echo $cityId;?>" + "|" + "<?php echo $stateId;?>" + "|" + "<?php echo $countryId;?>";
setCookie("searchresults_loc", locString);
var listings_with_localities = <?php echo $listings_with_localities; ?>;
var $categorypage = {};
$categorypage.categoryId = "";
$categorypage.subCategoryId = "";
$categorypage.LDBCourseId = 1;
$categorypage.cityId = '<?php echo $cityId;?>';
$categorypage.key  = "searchresults";
$categorypage.ajaxurl  = "";
$categorypage.filterTrackingURL  = "";
$categorypage.currentUrl = '<?php echo $currentUrl;?>';
$categorypage.NaukrilearningTrack = "";
subcatSameAsldbCourseCategoryPage = "";
dynamicHeightByIds = {};
studyAbroad = 0;
var searchSponsoredIds = [];
var $searchPage = {};
$searchPage.studyAbroadIds = [];
$searchPage.instituteWithMultipleCourseLocations = [];
$searchPage.registrationSource = "SEARCH_SEARCHHOME_CENTER_REQUEST_BROCHURE";
$searchPage.searchPageKey = "MAIN_SEARCH_PAGE";
$searchPage.requestBrochureActionType = "SEARCH_REQUEST_EBROCHURE";
var currentPageName = "SEARCH_PAGE";
</script>
