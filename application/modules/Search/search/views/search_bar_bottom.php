<div class="spacer20 clearFix"></div>
<div class="spacer10 clearFix"></div>
<div id="search_bottom_bar" style="visibility:visible;">
	<div id="search-box" style="border-top:1px solid #F0F0F0;">
		<?php
		if(SHOW_AUTOSUGGESTOR) {
		?>
			<form id="searchFormAlt" name="searchFormAlt" method="get" action="<?php echo SHIKSHA_HOME_URL; ?>/search/index" onsubmit="return false;">
		<?php
		} else {
			?>
			<form id="searchFormAlt" name="searchFormAlt" method="get" action="<?php echo SHIKSHA_HOME_URL; ?>/search/index" onsubmit="return submitSearchQuery('searchFormAlt');">
			<?php
		}
		?>
		
			<div class="search-outer">
				<span class="search-icn"></span>
				<input type="text" name="keyword" id="altkeyword" default="Enter Institute or Course Name" value="<?php if(!empty($urlparams['keyword'])){ echo htmlspecialchars($urlparams['keyword']); } else { echo 'Enter Institute or Course Name';} ?>" autocomplete="off"   onfocus="checkTextElementOnTransition(this,'focus')" onblur="checkTextElementOnTransition(this,'blur')" style="color:#565656;font-family: Arial, Helvetica, sans-serif !important;font-size:12px !important;">
			</div>
			<?php
			if(SHOW_AUTOSUGGESTOR) {
			?>
				<input type="button" class="orange-button flLt" value="Search" onclick="return trackUserAutoSuggestionAlt('bc'); return false;">
			<?php
			} else {
				?>
				<input type="button" class="orange-button flLt" value="Search" onclick="return submitSearchQuery('searchFormAlt');">
				<?php
			}
			?>
			<div style="clear:both;"></div>
			<div style="position:relative;">
				<div id="suggestions_container_alt" style="min-width:630px;top:3px;display:none;z-index:9999" onclick="handleOnclickOnSuggestionContAlt(event);"></div>
			</div>
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
			<input type="hidden" name="search_type" id="search_type_alt" autocomplete="off" value=""/>
			<input type="hidden" name="search_data_type" id="search_data_type" autocomplete="off" value=""/>
			<input type="hidden" name="sort_type" id="sort_type" autocomplete="off" value=""/>
			
			<!-- for campaign -->
			<!--<input type="hidden" name="utm_campaign" value="site_search"/>
			<input type="hidden" name="utm_medium" value="internal"/>
			<input type="hidden" name="utm_source" value="shiksha"/>-->
			<!-- for autosuggestor -->
			<input type="hidden" name="from_page" value="search_page_bottom"/>
			<input type="hidden" name="autosuggestor_suggestion_shown" id="autosuggestor_suggestion_shown"  value="<?php echo htmlspecialchars($urlparams['autosuggestor_suggestion_shown']); ?>"/>
			
			<input type="hidden" name="autosuggest_selected_filters" id="autosuggest_selected_filters_alt" value="" />
			<input type="hidden" name="autosuggest_selected_filter_values" id="autosuggest_selected_filter_values_alt" value="" />
		</form>
		<!--<div class="clearFix"></div>-->
	</div>
</div>

<?php
if(SHOW_AUTOSUGGESTOR) {
?>
	<script type="text/javascript">
		var userAutoSuggestorTrackingDataAlt = [];
		var AS_suggestion_shown_alt = -1;
		var autoSuggestorInstanceAlt;
		var formSubmittedAlt = false;
		var formPostInterval;
		var lastInputDataDictAlt;
		var searchInputKewordEleAlt = document.getElementById("altkeyword");
		function initializeAutoSuggestorInstanceAlt(){
			if (window.addEventListener){
				var ele = document.getElementById("altkeyword");
				ele.addEventListener('keyup', handleInputKeysAlt, false);
			} else if (window.attachEvent){
				var ele = document.getElementById("altkeyword");
				ele.attachEvent('onkeyup', handleInputKeysAlt);
			}
			autoSuggestorInstanceAlt = new AutoSuggestor("altkeyword" , "suggestions_container_alt", true);
			autoSuggestorInstanceAlt.callBackFunctionOnKeyPressed = handleAutoSuggestorKeyPressedAlt;
			autoSuggestorInstanceAlt.callBackFunctionOnMouseClick = handleAutoSuggestorMouseClickAlt;
			autoSuggestorInstanceAlt.callBackFunctionOnEnterPressed = handleAutoSuggestorEnterPressedAlt;
			autoSuggestorInstanceAlt.callBackFunctionOnInputKeysPressed = handleAutoSuggestorInputKeysPressedAlt;
			autoSuggestorInstanceAlt.callBackSuggestionContainerShown = handleSuggestionContainerShownAlt;
		}
		
		function handleAutoSuggestorInputKeysPressedAlt(dict){
			lastInputDataDictAlt = dict;
		}
		
		function handleAutoSuggestorKeyPressedAlt(dict){
			userAutoSuggestorTrackingDataAlt.push(dict);
		}
		
		function handleAutoSuggestorEnterPressedAlt(dict){
			lastInputDataDict = dict;
			userAutoSuggestorTrackingDataAlt.push(dict);
			if(dict["suggestion_shown"] != undefined){
				AS_suggestion_shown_alt = dict["suggestion_shown"];	
			}
			trackUserAutoSuggestionAlt();
		}

		function handleSuggestionContainerShownAlt(dict) {
			//enter this condition when autosuggestor container is shown
			if(!userAutoSuggestorTrackingDataAlt.hasOwnProperty("suggestionShown")) {
				if(dict.fds.length > 0){
					userAutoSuggestorTrackingDataAlt['suggestionShown'] = 1;
				}
			}
		}
		
		function handleAutoSuggestorMouseClickAlt(dict){
			userAutoSuggestorTrackingDataAlt.push(dict);
		}
		
		function handleInputKeysAlt(e){
			if(autoSuggestorInstanceAlt){
				autoSuggestorInstanceAlt.handleInputKeys(e);	
			}
		}
		
		var resetFormPost = function(){
			formSubmittedAlt = false;
			clearInterval(formPostInterval);
		}
	
		function trackUserAutoSuggestionAlt(actionType){
			if(formSubmittedAlt == false){
				formSubmittedAlt = false;
				clearInterval(formPostInterval);
				formPostInterval = setInterval(function(){resetFormPost();}, 2000);
				if(autoSuggestorInstanceAlt){
					if(actionType != undefined){
						if(actionType == "bc"){
							if(document.getElementById("suggestions_container_alt") && document.getElementById("suggestions_container_alt").style.display == "none"){
								AS_suggestion_shown_alt =  5;		
							} else {
								AS_suggestion_shown_alt =  6;
							}
							var dict = {};
							dict['spn'] = -1;
							dict['ui'] = (autoSuggestorInstanceAlt.text_input_by_user != '') ? autoSuggestorInstanceAlt.text_input_by_user : document.getElementById("altkeyword").value;
							dict['at'] = "bc";
							dict['sp'] = " ";
							userAutoSuggestorTrackingDataAlt.push(dict);
						}
					}
					saveAutoSuggestorDataAlt(userAutoSuggestorTrackingDataAlt, AS_suggestion_shown_alt);
					return false;
				} else {
					saveAutoSuggestorDataAlt();
				}
			}
		}
		
		function handleOnclickOnSuggestionContAlt(e) {
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
		
		function handleClickForAutoSuggestorAlt(e){
			if(document.getElementById("suggestions_container_alt")){
				document.getElementById("suggestions_container_alt").style.display = "none";
			}
		}
		
		function saveAutoSuggestorDataAlt(userAutoSuggestorTrackingDataAlt, suggestionShown){
			var comingFromAutosuggestor = isUserComingFromAutosuggestorAlt();
			if(comingFromAutosuggestor == true){
				document.getElementById('search_type_alt').value = 'institute';
			}
			if(TRACK_AUTOSUGGESTOR_RESULTS_JS && autoSuggestorInstanceAlt){
				var x = document.getElementsByName("autosuggestor_suggestion_shown");
				for(var i=0; i < x.length; i++){
					x[i].value = suggestionShown;
				}
				var jsonDecodedStr = getJsonDecodedString(userAutoSuggestorTrackingDataAlt);
				var suggestionShownStr = suggestionShown;
				var searchPageStr = "&page=search_page_bottom";
				var trackSuggestionShownStrAlt = "&suggestionShown="+userAutoSuggestorTrackingDataAlt['suggestionShown'];
				var queryString = "&autosuggestor="+jsonDecodedStr+"&autosuggestor_suggestion_shown="+suggestionShownStr + searchPageStr + trackSuggestionShownStrAlt;
				var trackURL = '/searchmatrix/SearchMatrix/logASQueries';
				new Ajax.Request(trackURL,{method:'post', parameters: (queryString), onSuccess:function (response) {
					formSubmittedAlt = false;
					submitSearchQuery('searchFormAlt');
				}});
			} else {
				return submitSearchQuery('searchFormAlt');
			}
		}
		
		function isUserComingFromAutosuggestorAlt(){
			var flag = false;
			if(lastInputDataDictAlt != undefined && lastInputDataDictAlt != null){
				var dictKeys = ShikshaHelper.getDictionaryKeys(lastInputDataDictAlt);
				var inputText = "";
				var wordsAchieved = [];
				var finalDropDownSuggestions = [];
				if(ShikshaHelper.in_array('ibt', dictKeys)){
					inputText = lastInputDataDictAlt['ibt'];
				}
				if(searchInputKewordEleAlt){
					inputText = searchInputKewordEleAlt.value;
				}
				if(ShikshaHelper.in_array('wa', dictKeys)){
					wordsAchieved = lastInputDataDictAlt['wa'];
				}
				if(ShikshaHelper.in_array('fds', dictKeys)){
					finalDropDownSuggestions = lastInputDataDictAlt['fds'];
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
