<div class="instituteLists">
<script>
function removeCookiesOnZeroResult(key)
{ 
        var filterCookieName = 'filters-' + key;
        var cookieVal = $j.parseJSON(base64_decode(getCookie(filterCookieName)));
        cookieVal['degreePref'] = [];
        cookieVal['locality'] = [];
        cookieVal['courseexams'] = [];
        cookieVal['fees'] = [];
        var emptyCookie = true;
        var dictKeys = ShikshaHelper.getDictionaryKeys(cookieVal);
        for(k in dictKeys){
			var listKey = dictKeys[k];
			if(typeof cookieVal[listKey] != "undefined" && cookieVal[listKey].length > 0) {
				emptyCookie = false;
			}
        }
        if(emptyCookie){
                setCookie(filterCookieName, "");
        } else {
                var filtersString = convertFilterDictionaryIntoString(cookieVal);
                filtersString = base64_encode(filtersString);
                setCookie(filterCookieName, filtersString);
        }
}


</script>
<?php
	$CI_INSTANCE->config->load('categoryPageConfig');
	$subcategoriesForRnR = $CI_INSTANCE->config->item('CP_SUB_CATEGORY_NAME_LIST');
	if($subcategoriesForRnR[$request->getSubCategoryId()] && $zeroResultFlag) {
		$zeroResultDescription = "";
		if(!empty($zero_result_request)){ //Not an ajax call, directly landing from url
			$zeroResultKey 		= "URL_" .  $request->getSubCategoryId();
			$zeroResultMetaData  = $zero_result_request->getMetaData();
			$zeroResultPageTitle = $zeroResultMetaData['title'];
			$pos = strpos($zeroResultPageTitle, ' - Shiksha.com');
			if($pos)
				$zeroResultPageTitle = substr($zeroResultPageTitle, 0, $pos);
			$key = $zero_result_request->getPageKey();
			$zeroResultDescription = "View <a href='".$zero_result_request->getURL()."' onmousedown=removeCookiesOnZeroResult('$key');><b>" . $zero_result_categorypage_count . "</b> ".$zeroResultPageTitle."</a> and refine further by affiliation, fees, exam accepted and locality.";
	} else {	
			$zeroResultKey = "AJAX_" .  $request->getSubCategoryId();
			$zeroResultDescription = "Please change your refinement criteria above.";
		}
	?>
		<script>
		if(typeof $categorypage != "undefined"){
			if($categorypage.subCategoryId == 23 || $categorypage.subCategoryId == 56){
				if(typeof pageTracker != "undefined" ){
					var step = '<?php echo $zeroResultStep?>_' + $categorypage.subCategoryId;
					pageTracker._trackEvent("RNR_PH2_ZERO_RESULTS_TRIGGER_STEP", step, step);
					pageTracker._trackEvent("RNR_PH1_ZERO_RESULTS", $categorypage.subCategoryId, $categorypage.subCategoryId);
				}
			}
		}
		</script>
		<div class="zero-result-box" style="border-bottom:none;">
			<i class="zoom-icon"></i>
			<div class="zero-result-title">Sorry, no results found for your selected criteria.</div>
			<p><?php echo $zeroResultDescription;?></p>
		</div>
	<?php
	}
	if(!$institutes) {
		if($request->getPageNumberForPagination() > 1) {
			$urlRequest = clone $request;
			$urlRequest->setData(array('pageNumber'=>1));
			$url = $urlRequest->getURL();
			header("location:".$url);
		}
		if(!in_array($request->getSubCategoryId(), array_keys($subcategoriesForRnR))){
			?>
			<h1 style="font-size:17px !important;">Sorry, no results were found matching your selection.<br/> Please alter your refinement options above.</h1>
			<?php
			
		}
	} else {
       
		if($request->getSubCategoryId() == 56) {
       
        $this->load->view('/categoryList/categoryPageSnippetsBeBetch');
        } else {
		$this->load->view('/categoryList/categoryPageSnippets');
		 }
		 
		$this->load->view('/categoryList/categoryPagePagination');
		$this->load->view('/categoryList/categoryPagePageNumbers');
	}
?>
	<div class="clearFix spacer10"></div>
	<div class="h-Rule"></div>
	<div class="clearFix"></div>
</div>