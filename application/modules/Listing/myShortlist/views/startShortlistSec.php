<div class="loader" id="loadingImage" style="position:absolute;display:none;z-index:9999;"><img src="/public/images/Loader2.GIF" /> Loading</div>
<div class="abroad-layer" id="overlayContainer" style="display: none;">
        <div class="abroad-layer-content clearfix">
            <div class="abroad-layer-title" id="overlayTitle"></div>
            <div id="overlayContent"></div>
        </div>
</div>
    
<div class="shorlist-tabs-wrap">
	<h2 class="shortlist-title2" style="font-size:34px">Start Shortlisting</h2>
<div class="shortlist-tab-info" id="shorlist-tab-search" style="display:block; position:relative;">
		    <input type="text" id="keywordSuggest" name="keywordSuggest" default="Enter college name to shortlist" value="Enter college name to shortlist" class="shortlist-txtfield" onfocus="this.hasFocus=true; checkTextElementOnTransition(this,'focus');" onblur="this.hasFocus=false; checkTextElementOnTransition(this,'blur');" autocomplete="off"/>
            <div id="suggestions_container_shortlist" style="z-index:2; position:absolute; width:95%;"></div>
            <p id="noInstituteFoundError" style="margin-top: 5px; color: #939393; font-size: 17px; width: 400px;display: none;">Sorry no institute selected.</p>
            <select name="shortlistedCourse" id="shortlistedCourse" class="" style="display:none; width:100%; margin: 15px 0;padding:8px 5px">
                <option value="">Please select a course that you want to shortlist</option>
            </select>
            <p id="noCourseFoundError" style="margin: 5px 0 10px; color: #939393; font-size: 17px; width: 400px;display: none;">Sorry no course selected.</p>
            <a id="shortListSearchBtn"  href="javascript:void(0);" class="shorlist-btn" style="padding:9px 10px; margin:15px auto 0; width:300px;" onclick="return searchShortListInstitute();"><i class="shortlist-sprite shrotlist-star"></i> <span class="btn-label">Add to Shortlist</span></a>
		    <input type="hidden" id="searchShortlistTrackingId" value= "1108"/>
            
		    <p id="customError" style="margin-top: 5px; color: #939393; font-size: 17px; width: 400px;display: none;"></p>
</div>
<div class="shortlist-tab-info" id="shorlist-tab-find" style="display:none">
    <div class="modify-search-sec clear-width" style="display:none;">
        <div class="flLt">
            <label>Showing results for:</label>
            <span class="selected-box" id="selected-exam"></span>
            <span class="selected-box" id="selected-location"></span>
        </div>
        <a href="javascript:void(0)" onclick="resetShortlistTabFind()" class="modify-search-btn flRt">Modify Search</a>
        <div class="clearFix"></div>
    </div>
    <p class="no-Results-info"  style="display:none;">No institutes found for your criteria. Please change your search.</p>
    <div class="clearFix"></div>
    <div id="showInstituteData" style="display:none">
    </div>
</div>
<div class="clearFix"></div>
</div>
