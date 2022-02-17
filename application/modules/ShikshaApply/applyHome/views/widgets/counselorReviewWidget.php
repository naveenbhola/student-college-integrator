<div class="_card">
    <div class="card_head tbl">
        <div class="tbl-cell">
          <h2 class="title" id="reviewHead">Student <?php echo " Review".(($totalReviewCount >1)?"s":"");?> (<?php echo $totalReviewCount?>)</h2>
          <?php if($totalReviewCount>0){?>
          <p class="vrfy_txt"><strong>Verified</strong> Reviews collected from Shiksha Study Abroad Counseling Service Users</p>
          <?php }?>
        </div>
        <div class="tbl-cell btn-cont">
          <a class="btn_blue writeReview" trackingkey="<?php echo $reviewPostTrackingKey; ?>">Write a Review</a>
        </div>
    </div>
    <div class="card_body clear_max">
        <?php
            foreach($reviewInfo as $key=>$value){
                $this->load->view('applyHome/widgets/reviewTuple',array('value'=>$value));
            }
        ?>
        <?php if($totalReviewCount==0){?>
        <p class="no_rvws">No review available</p>
        <?php } ?>
    </div>
    <?php if($totalReviewCount>REVIEW_PER_PAGE){?>
    <div id="uni_pagination_results">
    <div class="load_more">
      <a class="more_btn ripple" id="pgntnBtn"> Load More Reviews</a>
    </div>
    </div>
    <?php } ?>
</div>
