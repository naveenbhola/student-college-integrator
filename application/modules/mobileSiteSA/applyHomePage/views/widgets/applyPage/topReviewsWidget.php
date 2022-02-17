<div class="apply_bg">
  <div class="main_sectn box effect2">
      <h1>Overseas Admission Counseling <span>by Shiksha.com</span></h1>
      <div class="counseling-boxes">
          <div>
              <i class="counslng-icon"></i>
              <span>Student Centric Process</span>
          </div>
          <div>
              <i class="chat-icon"></i>
              <span>Instant chat Availability</span>
          </div>
          <div>
              <i class="univ-icon"></i>
              <span>Wide Choice of Universities</span>
          </div>
          <div>
              <i class="personalised-icon"></i>
              <span>100% Free &amp; Personalised</span>
          </div>
      </div>
  </div>
    <div class="apply-carouselList">
        <ul class="slider-list">
        <?php $active=' active'; $classes = ' slide fade ';
            foreach($topReviews as $review){ ?>
            <li class="<?php echo $active; ?>">
                <div class="apply-carouselDiv reviewSlide <?php echo $classes; ?>">
                    <span><?php echo ucfirst($review['serviceReviewText']); ?></span>
                    <p><strong><?php echo htmlentities($review['studentName']); ?></strong></p>
                    <p><?php echo ($review['admittedTo']==''?'':'Admitted to '.$review['admittedTo']); ?></p>
                </div>
            </li>
        <?php $classes = ' slide fade slide--up';$active=' inactiveSlide'; } ?>
        </ul>
        <div class="apply-caraouselbullets">
            <ul>
            <?php $active='active'; foreach($topReviews as $review){?>
                <li class="<?php echo $active; ?>"></li>
            <?php $active = 'inactive';} ?>
            </ul>
        </div>
    </div>

  <div class="Rated-box">  
    <div class="review-rateTab">
        <p><strong><?php echo $saCounsellingRatingData['overallRating']; ?></strong></p>
        <div class="Rating-block">
            <div class="Rating-Fullblock" style="width: <?php echo $starRatingWidth;?>;"></div>
        </div>
        <a href="<?php echo $saReviewPage; ?>" class="review-tag"><?php echo $saCounsellingRatingData['ratingCount']; ?> Reviews</a>
    </div>
    <a href="#examScoreWarningLayer" class="evaluationCall-btn book-btn gepec-btn" id="sticky" data-rel="dialog" data-transition="slide" gepectrackingid="906" profileevaluationtrackingid="<?php echo (!$onRMCSuccessFlag?909:912); ?>"><?php echo $profEvalCTAText; ?></a>
</div>
</div>
