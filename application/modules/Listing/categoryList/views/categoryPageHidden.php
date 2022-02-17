<?php
$RNRPage = "false";
if($categoryPageTypeFlag == CP_NEW_RNR_URL_TYPE && in_array($request->getSubCategoryId(), $subcategoriesChoosenForRNR)){
	$newRequest = clone $request;
	$URLPgnoUpdate['pageNumber'] = 1;
	$newRequest->setData($URLPgnoUpdate);
	$urlData = $newRequest->urlManagerObj->getURL();
	$ajaxURL = $urlData['suffix'];
	$filterTrackingURL = '/categoryList/CategoryList/rnrTrackFilters/'. $request->getPageKeyString();
	$RNRPage = "true";
	
} else {
	$ajaxURL = '/categoryList/CategoryList/categoryPage/'.$request->getCategoryId()."-".$request->getSubCategoryId()."-".$request->getLDBCourseId()."-0-0-".$request->getCityId()."-".$request->getStateId()."-".$request->getCountryId()."-".$request->getRegionId()."-none-1-".$request->isNaukrilearningPage();
	$filterTrackingURL = '/categoryList/CategoryList/trackFilters/'.$request->getCategoryId()."-".$request->getSubCategoryId()."-".$request->getLDBCourseId()."-".$request->getLocalityId()."-".$request->getZoneId()."-".$request->getCityId()."-".$request->getStateId()."-".$request->getCountryId()."-".$request->getRegionId()."-none-1-".$request->isNaukrilearningPage();
}
$url = $ajaxURL;

$requestUrl = clone $request;
$currentUrl = $requestUrl->getURL();
global $MBA_SCORE_RANGE;
global $MBA_SCORE_RANGE_CMAT;
global $MBA_SCORE_RANGE_GMAT;
global $ENGINEERING_EXAMS_REQUIRED_SCORES;
global $MBA_EXAMS_REQUIRED_SCORES;
global $MBA_NO_OPTION_EXAMS;
global $MBA_PERCENTILE_RANGE_MAT;
global $MBA_PERCENTILE_RANGE_XAT;
global $MBA_PERCENTILE_RANGE_NMAT;

$totalInstituteCount = 0;
if($categoryPage->getTotalNumberOfInstitutes() > 0) {
    $totalInstituteCount = $categoryPage->getTotalNumberOfInstitutes();
}
?>
<input type="hidden" id="category_unified_id" value="<?=$request->getCategoryId()?>" />
<input type="hidden" id="categorypage_unified_thankslayer_identifier" value="" />
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
$categorypage.currentUrl = "<?=$currentUrl?>";
$categorypage.NaukrilearningTrack = "<?=$request->isNaukrilearningPage()?"/nl":""?>";
$categorypage.localityId = "<?=$request->getLocalityId()?>";
$categorypage.instituteCountOnPage = "<?=$totalInstituteCount;?>";
$categorypage.isRNRCatPage = "<?=$RNRPage?>";
$categorypage.cityId = "<?=$request->getCityId();?>";
$categorypage.stateId = "<?=$request->getStateId();?>";
$categorypage.countryId = "<?=$request->getCountryId();?>";
$categorypage.examName = "<?=$request->getExamName()?>";
localityArray = new Array();
studyAbroad = 0;
var STUDY_ABROAD_TRACKING_KEYWORD_PREFIX = "";
</script>

<script>
var examListByCategory = <?=json_encode($exam_list);?>;
var MBA_SCORE_RANGE = <?=json_encode($MBA_SCORE_RANGE);?>;
var MBA_SCORE_RANGE_CMAT  = <?=json_encode($MBA_SCORE_RANGE_CMAT);?>;
var MBA_SCORE_RANGE_GMAT  = <?=json_encode($MBA_SCORE_RANGE_GMAT);?>;
var ENGINEERING_EXAMS_REQUIRED_SCORES = <?=json_encode($ENGINEERING_EXAMS_REQUIRED_SCORES);?>;
var MBA_EXAMS_REQUIRED_SCORES = <?=json_encode($MBA_EXAMS_REQUIRED_SCORES);?>;
var MBA_NO_OPTION_EXAMS = <?=json_encode($MBA_NO_OPTION_EXAMS);?>;
var MBA_PERCENTILE_RANGE_MAT = <?=json_encode($MBA_PERCENTILE_RANGE_MAT);?>;
var MBA_PERCENTILE_RANGE_XAT = <?=json_encode($MBA_PERCENTILE_RANGE_XAT);?>;
var MBA_PERCENTILE_RANGE_NMAT = <?=json_encode($MBA_PERCENTILE_RANGE_NMAT);?>;
</script>
