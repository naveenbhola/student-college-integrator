<?php
$originalPageType = $page_type;
if(empty($page_type)){
	$page_type = $_REQUEST['from_page'];
}
$searchPageType = "search_page_top";
if(!empty($page_type)){
	switch($page_type){
		case 'account_settings_page':
			$searchPageType = "search_bar_account_setting";
			break;
		default:
			$searchPageType = $page_type;
	}
}
?>
<div id="search-box">
	<?php
	if(SHOW_AUTOSUGGESTOR) {
	?>
		<form id="searchForm" name="searchForm" method="get" action="<?php echo SHIKSHA_HOME_URL; ?>/search/index" onsubmit="return false;">
	<?php
	} else {
		?>
		<form id="searchForm" name="searchForm" method="get" action="<?php echo SHIKSHA_HOME_URL; ?>/search/index" onsubmit="return submitSearchQuery();">
		<?php
	}
	?>
		<div class="search-outer">
			<span class="search-icn"></span>
			<input type="text" name="keyword" id="keyword" default="Enter Institute or Course Name" value="<?php if(!empty($urlparams['keyword'])){ echo htmlspecialchars($urlparams['keyword']); } else { echo 'Enter Institute or Course Name';} ?>" onfocus="checkTextElementOnTransition(this,'focus')" onblur="checkTextElementOnTransition(this,'blur')" autocomplete="off" style="color:#565656;">
		</div>
		<?php
		if(SHOW_AUTOSUGGESTOR) {
		?>
			<input type="button" class="orange-button flLt" value="Search" onclick="return trackUserAutoSuggestion('bc'); return false;">
		<?php
		} else {
			?>
			<input type="button" class="orange-button flLt" value="Search" onclick="return submitSearchQuery();">
			<?php
		}
		?>
		<div style="clear:both;"></div>
		<div id="suggestions_container" style="min-width:630px;top:45px;display:none;z-index:9999" onclick="handleOnclickOnSuggestionCont(event);"></div>
		<!-- count fields -->
		<?php
		if($urlparams['search_source'] == "SEARCH"){
		?>
			<input type="hidden" name="start" type="hidden" id="start" autocomplete="off" value="<?php echo htmlspecialchars($urlparams['start']); ?>"/>
			<input type="hidden" name="institute_rows" id="institute_rows" autocomplete="off" value="<?php echo htmlspecialchars($urlparams['institute_rows']); ?>"/>
			<input type="hidden" name="content_rows" id="content_rows" autocomplete="off" value="<?php echo htmlspecialchars($urlparams['content_rows']); ?>"/>
		<?php
		} else {
			?>
			<input type="hidden" name="start" type="hidden" id="start" autocomplete="off" value=""/>
			<input type="hidden" name="institute_rows" id="institute_rows" autocomplete="off" value=""/>
			<input type="hidden" name="content_rows" id="content_rows" autocomplete="off" value=""/>
			<?php
		}
		?>
		<!-- location fields -->
		<input type="hidden" name="country_id" id="country_id" autocomplete="off" value=""/>
		<input type="hidden" name="city_id" id="city_id" autocomplete="off" value=""/>
		<input type="hidden" name="zone_id" id="zone_id" autocomplete="off" value=""/>
		<input type="hidden" name="locality_id" id="locality_id" autocomplete="off" value=""/>
		<!-- course fields -->
		<input type="hidden" name="course_level" id="course_level" autocomplete="off" value=""/>
		<input type="hidden" name="course_type" id="course_type" autocomplete="off" value=""/>
		<!-- duration fields -->
		<input type="hidden" name="min_duration" id="min_duration" autocomplete="off" value=""/>
		<input type="hidden" name="max_duration" id="max_duration" autocomplete="off" value=""/>
		<!-- search options fields -->
		<input type="hidden" name="search_type" id="search_type" autocomplete="off" value=""/>
		<input type="hidden" name="search_data_type" id="search_data_type" autocomplete="off" value=""/>
		<input type="hidden" name="sort_type" id="sort_type" autocomplete="off" value=""/>
		
		<!-- for campaign -->
		<!--<input type="hidden" name="utm_campaign" value="site_search"/>
		<input type="hidden" name="utm_medium" value="internal"/>
		<input type="hidden" name="utm_source" value="shiksha"/>-->
		<input type="hidden" name="cpgs_param" value="<?php echo $subcat_id_course_page."_".$course_pages_tabselected;?>"/>
		<input id="from_page" type="hidden" name="from_page" value="<?php echo $searchPageType;?>" />
		<!-- for autosuggestor -->
		<input type="hidden" name="autosuggestor_suggestion_shown" id="autosuggestor_suggestion_shown"  value="<?php echo htmlspecialchars($urlparams['autosuggestor_suggestion_shown']); ?>"/>
		
		<input type="hidden" name="autosuggest_selected_filters" id="autosuggest_selected_filters" value="" />
		<input type="hidden" name="autosuggest_selected_filter_values" id="autosuggest_selected_filter_values" value="" />
	</form>
	<!--<div class="clearFix"></div>-->
</div>
<?php
if(SHOW_AUTOSUGGESTOR) {
?>
	<script type="text/javascript">
		var userAutoSuggestorTrackingData = [];
		var AS_suggestion_shown = -1;
		var autoSuggestorInstance;
		var formSubmitted = false;
		var formPostInterval;
		var lastInputDataDict;
		var searchPageType = '<?php echo $searchPageType;?>';
		var originalPageType = '<?php echo $originalPageType;?>';
		var searchInputKewordEle = document.getElementById("keyword");
		function initializeAutoSuggestorInstance(){
			if (window.addEventListener){
				var ele = document.getElementById("keyword");
				ele.addEventListener('keyup', handleInputKeys, false);
			} else if (window.attachEvent){
				var ele = document.getElementById("keyword");
				ele.attachEvent('onkeyup', handleInputKeys);
			}
			autoSuggestorInstance = new AutoSuggestor("keyword" , "suggestions_container", true);
			autoSuggestorInstance.callBackFunctionOnKeyPressed = handleAutoSuggestorKeyPressed;
			autoSuggestorInstance.callBackFunctionOnMouseClick = handleAutoSuggestorMouseClick;
			autoSuggestorInstance.callBackFunctionOnEnterPressed = handleAutoSuggestorEnterPressed1;
			autoSuggestorInstance.callBackFunctionOnInputKeysPressed = handleAutoSuggestorInputKeysPressed;
			autoSuggestorInstance.callBackSuggestionContainerShown = handleSuggestionContainerShown;
		}
		
		function handleAutoSuggestorInputKeysPressed(dict){
			lastInputDataDict = dict;
		}
		
		function handleAutoSuggestorKeyPressed(dict){
			userAutoSuggestorTrackingData.push(dict);
		}
		
		function handleAutoSuggestorEnterPressed1(dict){
			lastInputDataDict = dict;
			userAutoSuggestorTrackingData.push(dict);
			if(dict["suggestion_shown"] != undefined){
				
				AS_suggestion_shown = dict["suggestion_shown"];	
			}
			trackUserAutoSuggestion();
		}

		function handleSuggestionContainerShown(dict) {
			//enter this condition when autosuggestor container is shown
			if(!userAutoSuggestorTrackingData.hasOwnProperty("suggestionShown")) {
				if(dict.fds.length > 0){
					userAutoSuggestorTrackingData['suggestionShown'] = 1;
				}
			}
		}
		
		function handleAutoSuggestorMouseClick(dict){
			userAutoSuggestorTrackingData.push(dict);
		}
		
		function handleInputKeys(e){
			if(autoSuggestorInstance){
				autoSuggestorInstance.handleInputKeys(e);	
			}
		}
		
		var resetFormPost = function(){
			formSubmitted = false;
			clearInterval(formPostInterval);
		}
		
		function trackUserAutoSuggestion(actionType){
			if(formSubmitted == false){
				formSubmitted = false;
				clearInterval(formPostInterval);
				formPostInterval = setInterval(function(){resetFormPost();}, 2000);
				if(autoSuggestorInstance){
					if(actionType != undefined){
						if(actionType == "bc"){
							if(document.getElementById("suggestions_container") && document.getElementById("suggestions_container").style.display == "none"){
								AS_suggestion_shown =  5;		
							} else {
								AS_suggestion_shown =  6;
							}
							var dict = {};
							dict['spn'] = -1;
							dict['ui'] = (autoSuggestorInstance.text_input_by_user != '') ? autoSuggestorInstance.text_input_by_user : document.getElementById("tempkeyword").value;
							dict['at'] = "bc";
							dict['sp'] = " ";
							userAutoSuggestorTrackingData.push(dict);
						}
					}
					saveAutoSuggestorData(userAutoSuggestorTrackingData, AS_suggestion_shown);
					return false;
				} else {
					saveAutoSuggestorData();
				}
			}
		}
		
		function handleOnclickOnSuggestionCont(e) {
			if(!e){
				e = window.event;
			}
			if (e.cancelBubble) {
				e.cancelBubble = true;
			} else {
				if(e.stopPropagation) {
					e.stopPropagation();
				}
			}
		}
		
		function handleClickForAutoSuggestor(e){
			if(document.getElementById("suggestions_container")){
				document.getElementById("suggestions_container").style.display = "none";
			}
		}
		
		function saveAutoSuggestorData(userAutoSuggestorTrackingData, suggestionShown){
			var comingFromAutosuggestor = isUserComingFromAutosuggestor();
			if(comingFromAutosuggestor == true){
				document.getElementById('search_type').value = 'institute';
			}
			if(document.getElementById("from_page")){
				if(originalPageType != 'account_settings_page'){
					document.getElementById("from_page").value = 'search_page_top';	
				}
			}
			if(TRACK_AUTOSUGGESTOR_RESULTS_JS && autoSuggestorInstance){
				var x = document.getElementsByName("autosuggestor_suggestion_shown");
				for(var i=0; i < x.length; i++){
					x[i].value = suggestionShown;
				}
				var jsonDecodedStr = getJsonDecodedString(userAutoSuggestorTrackingData);
				var suggestionShownStr = suggestionShown;
				var searchPageStr = "&page="+searchPageType;
				var trackSuggestionShownStr = "&suggestionShown="+userAutoSuggestorTrackingData['suggestionShown'];
				var queryString = "&autosuggestor="+jsonDecodedStr+"&autosuggestor_suggestion_shown="+suggestionShownStr + searchPageStr + trackSuggestionShownStr;
				var trackURL = '/searchmatrix/SearchMatrix/logASQueries';
				new Ajax.Request(trackURL,{method:'post', parameters: (queryString), onSuccess:function (response) {
					formSubmitted = false;
					submitSearchQuery();
				}});
			} else {
				return submitSearchQuery();
			}
		}
		
		function isUserComingFromAutosuggestor(){
			var flag = false;
			if(lastInputDataDict != undefined && lastInputDataDict != null){
				var dictKeys = ShikshaHelper.getDictionaryKeys(lastInputDataDict);
				var inputText = "";
				var wordsAchieved = [];
				var finalDropDownSuggestions = [];
				if(ShikshaHelper.in_array('ibt', dictKeys)){
					inputText = lastInputDataDict['ibt'];
				}
				if(searchInputKewordEle){
					inputText = searchInputKewordEle.value;
				}
				if(ShikshaHelper.in_array('wa', dictKeys)){
					wordsAchieved = lastInputDataDict['wa'];
				}
				if(ShikshaHelper.in_array('fds', dictKeys)){
					finalDropDownSuggestions = lastInputDataDict['fds'];
				}
				inputText = ShikshaHelper.trim(inputText);
				inputText = inputText.toLowerCase();
				wordsAchievedStr = wordsAchieved.join(" ");
				wordsAchievedStr = wordsAchievedStr.toLowerCase();
				wordsAchievedStr  = ShikshaHelper.trim(wordsAchievedStr);
				
				
				if(inputText == wordsAchievedStr){
					flag = true;
				}
				if(flag != true){
					for(key in finalDropDownSuggestions){
						if(flag != true){
							var tempSuggestion = finalDropDownSuggestions[key].toLowerCase();
							tempSuggestion = ShikshaHelper.trim(tempSuggestion);
							if(inputText == tempSuggestion){
								flag = true;
								break;
							}	
						}
					}
				}
			}
			return flag;
		}
	</script>
<?php
}
?>
