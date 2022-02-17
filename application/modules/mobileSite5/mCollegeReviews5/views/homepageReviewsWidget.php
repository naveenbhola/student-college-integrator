
        <div class="colgRvwHP revewNav">
            <h2 class="rv_head">Reviews</h2>
            
            <ul class="navLatstTop bordRdus3px">
                <li id="latestReviewTab"><a class="actve" onclick="showTabularReviews('latest','<?=$stream?>','<?=$baseCourse?>','<?=$educationType?>','<?=$substream?>')"><span><i class="icn-lst icn-watch"></i><label>Latest</label></span></a></li>
                <li id="topRankedReviewTab"><a onclick="showTabularReviews('TopRated','<?=$stream?>','<?=$baseCourse?>','<?=$educationType?>','<?=$substream?>')"><span><i class="icn-lst icn-star"></i><label>Top Ranked</label></span></a></li>
            </ul>
            <div id="reviewWidget">
            <?php $this->load->view('showReviewPopularLatestWidget'); ?>
            </div>
            
            <?php if($totalReviews > $ReviewPerPage){ ?>
                <a class="rv_gryBtn1 ldmre" id="loadMoreButton" stream='<?=$stream?>' baseCourse='<?=$baseCourse?>' educationType='<?=$educationType?>' substream='<?=$substreamId?>' onclick="loadMoreReviews(this)">Load More</a>
            <?php } ?>

            <div style='font-size: 0.8em; padding:0px; margin: 5px;'>
                <b style="float:left; display:block;">Disclaimer : </b><span style="display: block; margin-left: 80px; color: rgb(112, 112, 112);">All reviews are submitted by current students & alumni of respective colleges and duly verified by Shiksha.</span>
            </div>
            
        </div>
        
<style>
.disabled {
   pointer-events: none;
   cursor: default;
}
</style>


<script>
    
  
    var item_per_page = 5;
    var pageNo = 1;
    //var total_pages = '<?php echo $total_pages; ?>';
    var total_pages = '<?php echo $totalCollegeCards; ?>';
    var seoUrl = '<?php echo $seoUrl; ?>';
    var reviewPaginationFlag = true;
    var track_click = 1;

    var stream = ""+"<?=$stream;?>";
    var baseCourse = ""+"<?=$baseCourse;?>";
    var substream = ""+"<?=$substream;?>";
    var educationType = ""+"<?=$educationType;?>";
    
</script>
<script>
      // ankit code

    $('.colgRvwSlidrBx-inner').unbind(); 
     var widgetHandler = new CollegeReviewSlider(".colgRvwSlidrBx-inner",item_per_page,pageNo,total_pages,seoUrl);
     widgetHandler.bindCollegeReviewWidget();

      
</script>