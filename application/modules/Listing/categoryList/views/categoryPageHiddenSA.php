<?php
$url = '/categoryList/CategoryList/categoryPage/'.$request->getCategoryId()."-".$request->getSubCategoryId()."-".$request->getLDBCourseId()."-0-0-".$request->getCityId()."-".$request->getStateId()."-".$request->getCountryId()."-".$request->getRegionId()."-none-1-".$request->isNaukrilearningPage();
$filterTrackingURL = '/categoryList/CategoryList/trackFilters/'.$request->getCategoryId()."-".$request->getSubCategoryId()."-".$request->getLDBCourseId()."-".$request->getLocalityId()."-".$request->getZoneId()."-".$request->getCityId()."-".$request->getStateId()."-".$request->getCountryId()."-".$request->getRegionId()."-none-1-".$request->isNaukrilearningPage();
$reorderFilterLocationListURL = '/categoryList/CategoryList/reorderFilterLocationList/'.$request->getCategoryId()."-".$request->getSubCategoryId()."-".$request->getLDBCourseId()."-".$request->getLocalityId()."-".$request->getZoneId()."-".$request->getCityId()."-".$request->getStateId()."-".$request->getCountryId()."-".$request->getRegionId()."-none-1-".$request->isNaukrilearningPage();
$requestUrl = clone $request;
$currentUrl = $requestUrl->getURL();
?>
<input id="category_unified_id" type="hidden" value="1">
<input id="categorypage_unified_thankslayer_identifier" type="hidden" value="">
<input id="methodName" type="hidden" value="getFeaturedCollegesForCountryPages">
<input id="subCategoryId" type="hidden" value="1">
<input id="country" type="hidden" value="">
<input id="cities" type="hidden" value="0">
<input id="intinstituteId" type="hidden" value="">
<script>
var listings_with_localities = <?php echo $listings_with_localities; ?>;
	if(typeof($categorypage) == 'undefined'){
	$categorypage = [];
}
$categorypage.categoryId = "<?=$request->getCategoryId()?>";
$categorypage.subCategoryId = "<?=$request->getSubCategoryId()?>";
$categorypage.LDBCourseId = "<?=$request->getLDBCourseId()?>";
$categorypage.cityId = "<?=$request->getCityId()?>";
$categorypage.key  = "<?=$request->getPageKey()?>";
$categorypage.ajaxurl  = "<?=$url?>";
$categorypage.filterTrackingURL  = "<?=$filterTrackingURL?>";
$categorypage.reorderFilterLocationListURL  = "<?=$reorderFilterLocationListURL?>";
$categorypage.currentUrl = "<?=$currentUrl?>";
$categorypage.NaukrilearningTrack = "<?=$request->isNaukrilearningPage()?"/nl":""?>";
$categorypage.localityId = "<?=$request->getLocalityId()?>";
localityArray = new Array();
studyAbroad = 1;
var STUDY_ABROAD_TRACKING_KEYWORD_PREFIX = "STUDY_ABROAD_";
</script>
