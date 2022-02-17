<div id="cateSearchBlock">
	<div class="search-outer-2">
		<div class="searchContents">
<form action="<?php echo SHIKSHA_ASK_HOME.'/search1'; ?>" id="cse-search-box" onsubmit="checkTextElementOnTransition(document.getElementById('keyword'),'focus');setGoogleSearchVal(encodeURI(document.getElementById('keyword').value)); if(document.getElementById('keyword').value=='' || document.getElementById('keyword').value=='Search Ask & Answer'){ document.getElementById('keyword').focus(); return false;} else {return true;}">
<!--div style="height:33px;overflow:hidden">
    <div class="ana_sOBx float_R">
        <div class="ana_sIBx"-->
        	<!--span class="search-icn">&nbsp;</span-->
            <!--input type="text" name="q" class="ana_sIpt" style="color:#ADA6AD" id="q" onblur="checkTextElementOnTransition(this,'blur')" onfocus="checkTextElementOnTransition(this,'focus')" default="Search Cafe" value="<?php //(isset($_COOKIE['searchText']) && isset($_REQUEST['q'])){echo htmlspecialchars($_COOKIE['searchText']);}else{ echo 'Search Cafe';}?>"/>-->
            <!--<input type="submit" name="sa" value="&nbsp;" class="ana_sBtn" />-->

	<input type="text" autocomplete="off"  default="Search Ask & Answer" id="keyword"  name = "keyword" onfocus="checkTextElementOnTransition(this,'focus')" onblur="checkTextElementOnTransition(this,'blur')"  style="color:#aaaaaa" class="searchField" value="<?php if(isset($_COOKIE['searchText']) && isset($_REQUEST['keyword'])){echo htmlspecialchars($_COOKIE['searchText']);}else{ echo 'Search Ask & Answer';}?>"/>
 
	<!--input type="hidden" name="keyword" id="keyword" autocomplete="off" value="" size = "35"/-->
	<input type="hidden" name="start" type="hidden" id="start" autocomplete="off" value="0"/>
	<input type="hidden" name="institute_rows" id="institute_rows" autocomplete="off" value="0"/>
	<input type="hidden" name="content_rows" id="content_rows" autocomplete="off" value="10"/>
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
	<input type="hidden" name="search_data_type" id="search_data_type" autocomplete="off" value="question"/>
	<input type="hidden" name="sort_type" id="sort_type" autocomplete="off" value=""/>
	<!-- for campaign -->
	<!--<input type="hidden" name="utm_campaign" value="site_search"/>
	<input type="hidden" name="utm_medium" value="internal"/>
	<input type="hidden" name="utm_source" value="shiksha"/>-->
	<!-- for autosuggestor -->
	<input type="hidden" name="from_page" value="<?php echo $searchPageType;?>" />
	<input id="autosuggestor_suggestion_shown" type="hidden" name="autosuggestor_suggestion_shown" value="-1"/>
	<!-- pankaj track params added for tracking search users-->
        <!--<input type="hidden" name="utm_campaign" value="ask_search"/>
        <input type="hidden" name="utm_medium" value="internal"/>
        <input type="hidden" name="utm_source" value="shiksha"/>-->
            <!--input type="submit" name="sa" value="Search Answer" class="gray-button" style="cursor:pointer" /-->
	    <input type="submit" name="sa" value="" class="course-search-btn" /> 
	    <input type="hidden" name="cpgs_param" value="<?php echo $subcat_id_course_page.'_'.$course_pages_tabselected;?>" />
        <!--/div>
    </div>
    
</div-->
</form>
	    </div>
	</div>
</div>	

<script>
function setGoogleSearchVal(val){
    tmpvalue = decodeURI(val);
    tmpvalue = tmpvalue.replace(/\+/g, "%2B");
    setCookie('searchText',tmpvalue,0);
}
</script>

