<div id="cateSearchBlock">
	<div class="search-outer-2">
		<div class="searchContents">
<form action="<?php echo SHIKSHA_ASK_HOME.'/search2'; ?>" id="cse-search-box" onsubmit="checkTextElementOnTransition(document.getElementById('q'),'focus');setGoogleSearchVal(encodeURI(document.getElementById('q').value)); if(document.getElementById('q').value=='' || document.getElementById('q').value=='Search Cafe'){ document.getElementById('q').focus(); return false;} else {return true;}">
<!--div id="course-search"-->
   <!--div class="course-search-outer"-->
            <input type="text" name="q" style="color:#aaaaaa" class="searchField" id="q" onblur="checkTextElementOnTransition(this,'blur')" onfocus="checkTextElementOnTransition(this,'focus')" default="Search Cafe" value="<?php if(isset($_COOKIE['searchText']) && isset($_REQUEST['q'])){echo htmlspecialchars($_COOKIE['searchText']);}else{ echo 'Search Cafe';}?>"/>            
            <input type="hidden" name="cx" value="004839486360526637444:znooniugfqo" />
            <input type="hidden" name="cof" value="FORID:10;NB:1" />
            <input type="hidden" name="ie" value="UTF-8" />
            
            <!-- pankaj track params added for tracking search users-->
            <!--<input type="hidden" name="utm_campaign" value="ask_search"/>
            <input type="hidden" name="utm_medium" value="internal"/>
            <input type="hidden" name="utm_source" value="shiksha"/>-->
            <input type="hidden" name="cpgs_param" value="<?php echo $subcat_id_course_page.'_'.$course_pages_tabselected;?>" />
            <!--input type="submit" name="sa" value="Search Answer" class="course-search-btn" style="cursor:pointer" /-->
	    <input type="submit" name="sa" value="" class="course-search-btn" /> 
    <!--/div-->    
<!--/div-->
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

