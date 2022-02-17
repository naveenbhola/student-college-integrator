<div class="apply_bg">
  <div class="main_sectn box effect2">
      <h1>Overseas Admission Counseling <span>by Shiksha.com</span></h1>
      <div class="counseling-boxes">
          <div>
              <i class="counslng-icon"></i>
              <span>Student Centric Process</span>
          </div>
          <div>
              <i class="univ-icon"></i>
              <span>Wide Choice of Universities</span>
          </div>
          <div>
              <i class="chat-icon"></i>
              <span>Instant chat Availability</span>
          </div>
          <div>
              <i class="personalised-icon"></i>
              <span>100% Free & Personalised</span>
          </div>
      </div>
  </div>
    <div class="apply-carouselList">
        <ul class="slider-list">
            <li class="active">
                <?php
                $liHtml = '<li class="clearfix inactiveSlide">';
                $classes = ' slide ';
                foreach ($counsellingReviewData as $key=>$review)
                {
                    $studentName = ucfirst($review['studentName']);
                    $reviewText = ucfirst($review['serviceReviewText']);
                    if($key > 0){
                        echo '</li>'.$liHtml;
                    }
                    ?>
                    <div class="apply-carouselDiv reviewSlide <?php echo $classes;?>">
                        <span><?php echo $reviewText; ?></span>
                        <p><strong><?php echo $studentName ?></strong><?php if (isset($review['admittedTo']) && !empty($review['admittedTo'])) { ?><strong>,</strong> Admitted to <?php echo $review['admittedTo']; }?></p>
                    </div>
                <?php
                $classes = ' slide fade slide--up';
                }
                ?>
            </li>
        </ul>
        <div class="apply-caraouselbullets">
            <ul class="bullet-list">
                <li class="active">
                    <?php
                    $liHtml2 = '</li><li>';
                    foreach ($counsellingReviewData as $key=>$review)
                    {
                        if($key > 0){
                            echo '</li>'.$liHtml2;
                        }
                    }
                    ?>
                </li>
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
        <a href="javaScript:void(0);" class="evaluationCall-btn gfpec" gfpecTrackingId="920" profileEvaluationTrackingId="<?php echo ((!$onRMCSuccessFlag)?923:926); ?>"><?php echo $profEvalCTAText; ?></a>
    </div>
</div>
