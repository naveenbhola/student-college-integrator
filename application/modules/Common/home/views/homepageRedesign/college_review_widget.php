<script>
var mobileSearch = 'true';
    var subcatId = '<?php echo $subcatId;?>';
    if(subcatId == '') {
        subcatId = "23";
    }
</script>
<div class="homeWdgt homeWdgt04 mB32">
     <div class="w01_head">
      <h1 class="wgt_HeadT1">MBA College Reviews </h1>
      <p class="wgt_HeadT2">By Alumni and Current Students</p>
     </div>
     <div class="n-colgrvw" id="searchBox">
      <input type="text" class="inptHomWidgt" placeholder="Search by College Name" name="keyword"  id="keywordSuggest" minlength="1" onfocus="getSuggestedSearch('','focus');" autocomplete="off">
      <i class="icons ic_search_gry" url="<?php echo SHIKSHA_HOME ?>" key="keywordSuggest"></i>
     </div>
      <div class="sugest-bx" id="tileSuggested" style="border: none;">
            <ul class="colgrvw-Sugstr" style="text-align: left; display: none;" id="suggestions_container"></ul>
            <ul class="cc-sugest" id="suggestedList"></ul>
       </div>
     <ul class="n-colgExtrnlLnk">
        <h2>Popular Review Collection</h2>
        <li><a href="<?php echo $widget_collegeReviewData[0]['seoUrl']; ?>"><?php echo $widget_collegeReviewData[0]['title']; ?></a></li>
        <li><a href="<?php echo $widget_collegeReviewData[1]['seoUrl']; ?>"><?php echo $widget_collegeReviewData[1]['title']; ?></a></li>
        <li><a class="n-colg-Extra-viewall-link" href="<?php echo base_url()?><?= MBA_COLLEGE_REVIEW ?>">View All</a></li>
     </ul>  
</div>
<?php foreach($widget_collegeReviewData as $data) { ?>
<h2 class="tileText" style='display: none;'>
   <a class="tileFinder" href="<?php echo $data['seoUrl'] ?>"><?php echo $data['title'] ?></a>
</h2>
<?php } ?>
