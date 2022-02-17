
    <div class="card_head">
       <h2 class="title" id="reviewHead">Student <?php echo " Review".(($totalReviewCount >1)?"s":"");?> (<?php echo $totalReviewCount?>)</h2>
       <p class="vrfy_txt"><strong>Verified</strong> Reviews collected from Shiksha Study Abroad Counseling Service Users</p>

    </div>
    <div class="card_head clear_max pad_0">
    	<input type="hidden" class="triggerReviewCheck" name="triggerReviewCheck" value="<?php echo $triggerReviewCheck; ?>">
       <p class="side_txt">Availed <?php echo htmlentities(ucfirst($counsellorInfo['firstName']));?>'s Counseling services? </p>
       <a class="btn_blue writeReview" trackingkey="<?php echo $reviewPostTrackingKey;?>">Write a Review</a>
    </div>
