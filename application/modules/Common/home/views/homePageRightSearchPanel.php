<?php
return;
$searchPageType = "big_searchbox";
$collegeOrInstituteRNR = $collegeOrInstituteRNR ? $collegeOrInstituteRNR : 'college';
if(!empty($page_type)){
	switch($page_type){
		case 'study_abroad':
			$searchPageType = "big_searchbox_sa";
			break;
		case 'listing_page':
			$searchPageType = "big_searchbox_lp";
			break;
		default:
			$searchPageType = $page_type;
	}
}
?>
<!--<div class="Fnt16" style="padding-top:4px;color:#0f0f0f"><b>Search Institutes &amp; Courses</b></div>-->
<form id="searchForm" name="searchForm" method="get" action="<?php echo SHIKSHA_HOME_URL; ?>/search/index" >
	<!--
	<input type="hidden" name="keyword" id="keyword" autocomplete="off" value="<?php //echo htmlspecialchars($keyword); ?>"/>
    <input type="hidden" name="location" id="location" autocomplete="off" value="<?php //echo htmlspecialchars($location); ?>"/>
    <input type="hidden" name="searchType" id="searchType" autocomplete="off" value="<?php //echo htmlspecialchars($searchType); ?>"/>
    <input type="hidden" name="cat_id" id="cat_id" autocomplete="off" value="<?php //echo htmlspecialchars($catID);?>"/>
    <input name="countOffsetSearch" id="countOffsetSearch" autocomplete="off" value="<?php //echo htmlspecialchars($countOffsetSearch); ?>"  type="hidden" />
    <input name="startOffSetSearch" id="startOffSetSearch" autocomplete="off" value="<?php //echo htmlspecialchars($startOffSetSearch); ?>" type="hidden" />
    <input name="subLocation" id="subLocation" autocomplete="off" value="<?php //echo htmlspecialchars($subLocation); ?>" type="hidden" />
    <input name="cityId" id="cityId" autocomplete="off" value="<?php //echo htmlspecialchars($cityId); ?>" type="hidden" />
    <input name="cType" id="cType" autocomplete="off" value="<?php //echo htmlspecialchars($cType); ?>" type="hidden" />
    <input name="courseLevel" id="courseLevel" autocomplete="off" value="<?php //echo htmlspecialchars($courseLevel); ?>" type="hidden" />
    <input name="subType" id="subType" autocomplete="off" value="<?php //echo htmlspecialchars($subType); ?>" type="hidden"/>
    <input name="showCluster" id="showCluster" autocomplete="off" value="<?php //echo (isset($showCluster) || empty($showCluster)) ? '-1' : htmlspecialchars($showCluster); ?>" type="hidden" />
    <input name="channelId" id="channelId" autocomplete="off" value="<?php //echo $channelId; ?>" type="hidden"/>
    <input type="hidden" name="utm_campaign" value="site_search"/>
    <input type="hidden" name="utm_medium" value="internal"/>
    <input type="hidden" name="utm_source" value="shiksha"/>
	<input id="autosuggestor_suggestion_shown" type="hidden" name="autosuggestor_suggestion_shown" value="-1"/>
	-->
	<input type="hidden" name="keyword" id="keyword" autocomplete="off" value=""/>
	<input type="hidden" name="start" type="hidden" id="start" autocomplete="off" value="0"/>
	<input type="hidden" name="institute_rows" id="institute_rows" autocomplete="off" value="-1"/>
	<input type="hidden" name="content_rows" id="content_rows" autocomplete="off" value="-1"/>
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
	<input id="cpgs_param_id1" type="hidden" name="cpgs_param" value="" />
	<input type="hidden" name="from_page" value="<?php echo $searchPageType;?>" />
	<!-- for autosuggestor -->
	<input id="autosuggestor_suggestion_shown" type="hidden" name="autosuggestor_suggestion_shown" value="-1"/>
	
	<input type="hidden" name="autosuggest_selected_filters" id="autosuggest_selected_filters" value="" />
	<input type="hidden" name="autosuggest_selected_filter_values" id="autosuggest_selected_filter_values" value="" />
</form>

<div style="position:relative; z-index:1; width:100%; float:left">
	<?php global $product;if( $product=='all'){ // This is defined in header.php ?>
	<div class="Fnt16" style="padding-bottom:5px"><b>Search Institutes &amp; Courses</b></div>
	<?php
	}
	if(SHOW_AUTOSUGGESTOR){
	?>
		<form method="get" onsubmit="checkTextElementOnTransition(document.getElementById('tempkeyword'),'focus');checkTextElementOnTransition(document.getElementById('templocation'),'focus'); return false;";>
	<?php
	} else {
	?>
		<form method="get" onsubmit="checkTextElementOnTransition(document.getElementById('tempkeyword'),'focus');checkTextElementOnTransition(document.getElementById('templocation'),'focus');validateSearch(1,0,1);return false;">	
	<?php
	}
	?>
	<input type="hidden" value="course" id="tempSearchType" />
	<input id="cpgs_param_id" type="hidden" name="cpgs_param" value="" />
	<div class="search-btn-col">
	<?php
	if(SHOW_AUTOSUGGESTOR){
	?>
		<input type="button" value="Search" class="orange-button" onclick="trackEventByGA('SearchClick','SEARCH_BTN'); trackUserAutoSuggestion('bc'); return false;"/>
	<?php
	} else {
	?>
		<input type="submit" value="Search" class="orange-button" onclick="trackEventByGA('SearchClick','SEARCH_BTN'); "/>
	<?php
	}
	$inputContainerWidth = "70%";
	if(SHOW_AUTOSUGGESTOR){
		$inputContainerWidth = "98%";	
	}
	?>
	</div>
	<div class="search-field-col">
    	
		<div class="flLt" style="width:<?php echo $inputContainerWidth;?>" id="tempKeywordHolder">
			<div>
				<?php
				if(SHOW_AUTOSUGGESTOR){
				?>
					<input type="text" style="width:<?php echo $inputContainerWidth;?>;color: #ADA6AD;padding:6px 5px;" class="universal-txt-field" autocomplete="off" value="Enter <?php echo ucfirst($collegeOrInstituteRNR);?> or Course Name" default="Enter <?php echo ucfirst($collegeOrInstituteRNR);?> or Course Name" id="tempkeyword"  onfocus="checkTextElementOnTransition(this,'focus')" onblur="checkTextElementOnTransition(this,'blur')"  onclick="handleOnclickOnSuggestionCont(event);"/>
					
				<?php
				} else {
				?>
					<input type="text" style="width:<?php echo $inputContainerWidth;?>;color: #ADA6AD;" class="homeShik_searchtextBox" autocomplete="off" value="Enter <?php echo ucfirst($collegeOrInstituteRNR);?> or Course Name" default="Enter <?php echo ucfirst($collegeOrInstituteRNR);?> or Course Name" id="tempkeyword"  onfocus="checkTextElementOnTransition(this,'focus')" onblur="checkTextElementOnTransition(this,'blur')"/>
				<?php
				}
				?>
			</div>
		</div>
		<?php
		if(SHOW_AUTOSUGGESTOR) {
		?>
			<div class="loc-field-col" id="tempLocationHolder" style="width:0px;display:none;">
				<input type="hidden" value="" default="Enter Location" id="templocation"/>
			</div>
		<?php
		} else {
		?>
			<div class="loc-field-col" id="tempLocationHolder">
				<div class="homeShik_textBoxBorder">
					<input type="text" style="color:#AdA6Ad" class="homeShik_searchtextBox" value="Enter Location" default="Enter Location" id="templocation"  onfocus="checkTextElementOnTransition(this,'focus')" onblur="checkTextElementOnTransition(this,'blur')"/>
				</div>
			</div>
		<?php
		}
		?>
	</div>
    
    <?php
		if(SHOW_AUTOSUGGESTOR){
		?>
			<div class="autosuggest_container" id="suggestion_output_container" style="min-width:476px;_width:476px;clear:both; z-index:999;position:absolute; left:0px; top:32px; background-color:#fff;display:none;" onclick="handleOnclickOnSuggestionCont(event);"></div>
		<?php
		}
		?>
    
</form>


<?php   
   $productSelect = isset($productSelect)?$productSelect:'course';
   $showProduct = isset($showProduct)?$showProduct:true;
?>    
<div style="<?php if(!$showProduct){ echo "display:none"; }?>; width:100%; float:left ">
	<!--<div class="search-radio-options">
		<input type="radio" name="searchRadio" value="course" onClick = "setSearchTypeGSP('course'); enableSuggestions('course');" <?php if($productSelect == 'course'){ echo "checked"; } ?> /> <span>Institutes &amp; Courses</span>&nbsp;&nbsp;&nbsp;
		<input type="radio" name="searchRadio" value="question" onClick = "setSearchTypeGSP('question'); enableSuggestions('question');" <?php if($productSelect == 'question'){ echo "checked"; } ?> /> <span>Ask &amp; Answer</span>&nbsp;&nbsp;&nbsp;
		<input type="radio" name="searchRadio" value="blog" onclick = "setSearchTypeGSP('blog'); enableSuggestions('blog');" <?php if($productSelect == 'blog'){ echo "checked"; } ?> /> <span>Articles</span>
	</div>-->
</div>
</div>

<?php
if(SHOW_AUTOSUGGESTOR){
?>
	<script type="text/javascript">
		var userAutoSuggestorTrackingData = [];
		var AS_suggestion_shown = -1;
		var autoSuggestorInstance;
		var searchPageType = '<?php echo $searchPageType;?>';
		var lastInputDataDict;
		var searchInputKewordEle = document.getElementById("tempkeyword");
		function initializeAutoSuggestorInstance(){
			
			var currentRadioButtonChecked = "course";
			if(document.getElementsByName("searchRadio")){
				var radioButtons = document.getElementsByName("searchRadio");
				for( i = 0; i < radioButtons.length; i++ ) {
					if(radioButtons[i].checked) {
						currentRadioButtonChecked =  radioButtons[i].value;
						break;
					}
				}
			}
			if (window.addEventListener && currentRadioButtonChecked == "course"){
				var ele = document.getElementById("tempkeyword");
				ele.addEventListener('keyup', handleInputKeys, false);
			} else if (window.attachEvent && currentRadioButtonChecked == "course"){
				var ele = document.getElementById("tempkeyword");
				ele.attachEvent('onkeyup', handleInputKeys);
			}
			autoSuggestorInstance = new AutoSuggestor("tempkeyword" , "suggestion_output_container", true);
			autoSuggestorInstance.callBackFunctionOnKeyPressed = handleAutoSuggestorKeyPressed;
			autoSuggestorInstance.callBackFunctionOnMouseClick = handleAutoSuggestorMouseClick;
			autoSuggestorInstance.callBackFunctionOnEnterPressed = handleAutoSuggestorEnterPressed;
			autoSuggestorInstance.callBackFunctionOnInputKeysPressed = handleAutoSuggestorInputKeysPressed;
		}
		
		function handleAutoSuggestorInputKeysPressed(dict){
			lastInputDataDict = dict;
		}
		
		function handleAutoSuggestorKeyPressed(dict){
			userAutoSuggestorTrackingData.push(dict);
		}
		
		function handleAutoSuggestorEnterPressed(dict){
			lastInputDataDict = dict;
			userAutoSuggestorTrackingData.push(dict);
			if(dict["suggestion_shown"] != undefined){
				AS_suggestion_shown = dict["suggestion_shown"];	
			}
			trackUserAutoSuggestion();
		}
		
		function handleAutoSuggestorMouseClick(dict){
			userAutoSuggestorTrackingData.push(dict);
		}
		
		function handleInputKeys(e){
			if($j('#_globalNav').length>0){$j('#_globalNav').css('z-index',0);}
			if(autoSuggestorInstance){
				autoSuggestorInstance.handleInputKeys(e);	
			}
		}
		
		function trackUserAutoSuggestion(actionType){
			if(autoSuggestorInstance){
				if(actionType != undefined){
					if(actionType == "bc"){
						if(document.getElementById("suggestion_output_container") && document.getElementById("suggestion_output_container").style.display == "none"){
							AS_suggestion_shown =  5;		
						} else {
							AS_suggestion_shown =  6;
						}
						var dict = {};
						dict['spn'] = -1;
						dict['ui'] = document.getElementById("tempkeyword").value;
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
		
		function enableSuggestions(type){
			if(SHOW_AUTOSUGGESTOR_JS){
				if(type != "course"){
 					var keyele = document.getElementById('tempkeyword');
					if (window.removeEventListener){
						keyele.removeEventListener('keyup',handleInputKeys,false);
					} else if(window.detachEvent){
						keyele.detachEvent('onkeyup',handleInputKeys);
					}
				} else {
					var keyele = document.getElementById("tempkeyword");
					if(typeof(handleInputKeys) == "function") {
						if (window.addEventListener){
							keyele.addEventListener('keyup',handleInputKeys,false);
						} else if(window.attachEvent){
							keyele.attachEvent('onkeyup', handleInputKeys);
						}
					}
				}
			}
		}
		
		function handleOnclickOnSuggestionCont(e){
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
			if(document.getElementById("suggestion_output_container")){
				document.getElementById("suggestion_output_container").style.display = "none";
			}
		}
		
		function submitSearch(){
			try{
				checkTextElementOnTransition($('tempkeyword'),'focus');
				checkTextElementOnTransition($('templocation'),'focus');
			} catch(e) {
			}
			var emptySearchSubmitErrorText = 'Please enter valid search query';
			var defaultSearchBarText = "Enter <?php echo ucfirst($collegeOrInstituteRNR);?> or Course Name";
			var keyWordValue = document.getElementById("tempkeyword").value;
			keyWordValue = ShikshaHelper.trim(keyWordValue);
			document.getElementById('keyword').value = keyWordValue;
			if(keyWordValue.length <= 0 || keyWordValue == defaultSearchBarText){
				return false;
			} else {
				setCookie("tsr", "searchbtn");
				var AS_selectedFilters = "";
				for(key in autoSuggestorInstance.filters_achieved){
					AS_selectedFilters += autoSuggestorInstance.filters_achieved[key] + "_$$_";
				}
				var AS_selectedFilterValues = "";
				for(key in autoSuggestorInstance.words_achieved){
					AS_selectedFilterValues += autoSuggestorInstance.words_achieved[key] + "_$$_";
				}
				
				document.getElementById("autosuggest_selected_filters").value = AS_selectedFilters;
				document.getElementById("autosuggest_selected_filter_values").value = AS_selectedFilterValues;
				document.searchForm.submit();
			}
		}
		
		function saveAutoSuggestorData(userAutoSuggestorTrackingData, suggestionShown){
			var comingFromAutosuggestor = isUserComingFromAutosuggestor();
			if(comingFromAutosuggestor == true){
				document.getElementById('search_type').value = 'institute';
			}
			if(TRACK_AUTOSUGGESTOR_RESULTS_JS && autoSuggestorInstance){
				if(document.getElementById('autosuggestor_suggestion_shown')){
					document.getElementById('autosuggestor_suggestion_shown').value = suggestionShown;
				}
				var jsonDecodedStr = getJsonDecodedString(userAutoSuggestorTrackingData);
				var suggestionShownStr = suggestionShown;
				var searchPageStr = "&page="+searchPageType;
				var queryString = "&autosuggestor="+jsonDecodedStr+"&autosuggestor_suggestion_shown="+suggestionShownStr + searchPageStr;
				var trackURL = '/searchmatrix/SearchMatrix/logASQueries';
				new Ajax.Request(trackURL,{method:'post', parameters: (queryString), onSuccess:function (response) {
					submitSearch();
					return false;
				}});
				return false;
			} else {
				submitSearch();
				return false;
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
