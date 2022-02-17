<style type="text/css">
#suggestions_container li span { font-size: 12px; color: #808080; border-bottom: 1px solid #f6f6f6; display: block; text-decoration: none; cursor: pointer; text-align: left; padding: 6px 10px; }
</style>
<div class="cr_searchBx" data-enhance="false">
    <h2 class="cr_searchBx_p1" style="margin-bottom: 10px;">Reviews & Ratings by Alumni & Current Students</h2>
    <h2 class="cr_searchBx_p2"><?=$totalReviewCount?>+ Reviews</h2>
    <div class="cr_inputsrchBx bordRdus3px">
        <div class="cr_inputsrchBxInnr" id="searchBox">
            <i class="icn-lst icn-search" onclick="gotoSearchPage('<?php echo SHIKSHA_HOME;?>','keywordSuggest','collegereviews')" style="cursor: pointer;"></i>
            <input type="text" placeholder="Search Reviews by College Name" name="keyword" id="keywordSuggest" minlength="1" onfocus="getSuggestedSearch('','focus');" autocomplete="off"/>
        </div>
        <div class="cr_sugestd openCollegeReviewSuggester" id="tileSuggested" style="display: none;">
            	<ul id="suggestions_container" style="text-align: left; display: block;"></ul>
                <ul id="suggestedList"></ul>
        </div>
    </div>
</div>
<?php
$this->load->view('autoSuggestorCollegeReviews');
?>
<script>
/**
 * getSuggestedSearch() is called on onfocus in search input,then search the college reviews tiles
 * @author : akhter
 * @param {totalSearch} totalSearch is the total size of list to view in the auto suggestion layer.
 **/
function getSuggestedSearch(totalSearch,action) {
    var finalHtml = '';
    var heading   = '';
    var flag = false;
    if (typeof(action) !='undefined' && ($('#suggestions_container li').length>0)) {
        totalSearch  = parseInt($('#suggestions_container li').length);
        flag = true;
    }
    var totalTile = (typeof(totalSearch) == 'undefined' || totalSearch == '') ? 6 : (6 - parseInt(totalSearch));
    if (totalTile>0 || flag) {
        if(totalSearch < 6){ heading = '<div style="border-bottom: 1px solid #f6f6f6;color: #a7a7a7;font-size: 10px;line-height: 20px;padding: 4px 10px;text-align: left;text-transform: uppercase;font-weight: bold;">SUGGESTED SEARCHES</div>';}
        $('.tileFinder').each(function(index){
            if (index < totalTile) {
                var titleTitle  = $(this).find('h2').text();
                var url         = $(this).attr('href');
                finalHtml = finalHtml + '<li title="'+titleTitle+'"><a href="'+url+'">'+titleTitle+'</a></li>';
            }
        });
        flag = false;
        $('#suggestedList').html(heading + finalHtml);
        $('#tileSuggested').slideDown(400);
    }else{
        flag = false;
        $('#suggestedList').html('');
    }   
}
//hide autosuggestor container click on outside of container
$(document).click(function (e)
{  
    var container = $("#tileSuggested");
    var container2 = $("#keywordSuggest, #searchBox");
    if (!container.is(e.target) && container.has(e.target).length === 0 && !container2.is(e.target) && container2.has(e.target).length === 0)
    {   
        if ($('#tileSuggested').length>0) {
		container.slideUp(400);
	}
    }
});

</script>
