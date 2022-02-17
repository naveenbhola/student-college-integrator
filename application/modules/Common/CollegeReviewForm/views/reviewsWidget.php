          <ul class="navLatstTop bordRdus3px">
          <li id="latestReviewTab" class="bordrgt"><a <?php if($orderOfReview=='latest'){ ?>class="actve"<?php } ?> onclick="showTabularReviews('latest')"><span><i class="icns-colgrvw icn-watch"></i>
                <h2 class="latestReview">Latest</h2>
        </span></a></li>
          <li id="topRankedReviewTab"><a <?php if($orderOfReview!='latest'){ ?>class="actve"<?php } ?> onclick="showTabularReviews('TopRated')"><span><i class="icns-colgrvw icn-star"></i>
                <h2 class="topRatedReview">Top Ranked</h2>
           </span></a></li>
        </ul>
          <p class="clr"></p>
        
        <div id="reviewWidget">
            <?php //$this->load->view('collegeReviewPopLatest'); ?>
            <?php $this->load->view('reviewsHomePageWidget'); ?>
        </div>
<style>
.disabled {
   pointer-events: none;
   cursor: default;
}
</style>

