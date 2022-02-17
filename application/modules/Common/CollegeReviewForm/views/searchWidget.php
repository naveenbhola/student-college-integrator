
<div id="searchOpacityLayer"></div>
<div id="searchReviewLayer" style="display: none;position:absolute;left: 29%;z-index: 10000;"></div>

<div class="cr_searchBx" style="padding-top:30px;">
  <div style="background-color: rgba(0, 0, 0, 0.55);display: inline-block;padding: 30px 60px;max-height: 100px;">
  <p>Reviews &amp; Ratings by Alumni &amp; Current Students</p>
  <strong><?=$totalReviewCount?>+ Reviews</strong>
  <div class="cr_inputsrchBx bordRdus3px">
    <div class="cr_inputsrchBxInnr" id="searchBox">
      <i class="icns-colgrvw icn-search" onclick="gotoSearchPage('<?php echo SHIKSHA_HOME;?>','keywordSuggest');" style="cursor: pointer;"></i>
      <input type="text" placeholder="Search Reviews by College Name" name="keyword" id="keywordSuggest" minlength="1" onfocus="getSuggestedSearch('','focus');" autocomplete="off"/>
    </div>
     <div class="cr_sugestd openCollegeReviewSuggester" id="tileSuggested" style="display: none;">
            	<ul id="suggestions_container" style="text-align: left; display: block;"></ul>
                <ul id="suggestedList"></ul>
        </div>
     
  </div>
  </div>
</div>
