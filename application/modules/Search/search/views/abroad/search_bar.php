<div class="search-box clearwidth">
    <form name="abroadsearchform" method="GET" action="/search-abroad/" onsubmit="submitSearchForm()">
    <div class="search-field">
        <i class="common-sprite search-icon"></i>
        <input id="keyword" type="text" class="search-width" name="keyword" default="Enter Institute or Course Name" value="<?php if(!empty($keyword)){ echo htmlspecialchars($keyword); } else { echo 'Enter Institute or Course Name';} ?>" onfocus="checkTextElementOnTransition(this,'focus')" onblur="checkTextElementOnTransition(this,'blur')" />
   		<input type="hidden" name="from_page" value="searchPage" />
    </div>
        <a href="javascript:void(0);" class="button-style" onclick="submitSearchForm();" style="border-radius:0 3px 3px 0; float:left; padding:7px; font:bold 14px Verdana, Geneva, sans-serif">Search</a>
    </form>
</div>

<script>
	function submitSearchForm() {
			var search_text = $j("#keyword").val();
                        if(search_text !== 'Enter Institute or Course Name'){
                                document.abroadsearchform.submit();
                        }

	}
</script>