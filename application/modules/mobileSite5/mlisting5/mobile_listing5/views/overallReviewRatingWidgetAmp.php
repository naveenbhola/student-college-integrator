<div class="rvw-h">
    <div class="rvwBlock">
        <div class="rvwLeft">
            <h2 class="ratingAll">Overall Rating <span>(Out of 5)</span></h2>
            <div class="rvwScore">
                <h3><?php echo $aggregateReviewsData['aggregateRating']['averageRating']; ?></h3>
                <div class="infrontRvws">
                    <i class="empty_stars">
                    <?php 
                    $percentage = round(($aggregateReviewsData['aggregateRating']['averageRating']/5 * 100));
                    ?>
                    <i class="full_starts w-<?php echo $percentage; ?>"></i>
                    </i>
                    <div class="refrnceTxt">
                        <span> Based on <?php echo $totalReviews; ?> Review<?php echo $totalReviews > 1 ? 's':''; ?></span>
                    </div>
                </div>
            </div>
            <div class="rvwProgress">
                <?php 
                foreach ($intervalsDisplayOrder as $interval => $intervalDisplayName) {
                    $percentage = round(($aggregateReviewsData['intervalRatingCount'][$interval]/$aggregateReviewsData['totalCount']) * 100);
                    $disableClass='';
                    if($percentage == 0) {
                      $disableClass='disablefilter';
                    }
                    if($aggregateReviewsData['intervalRatingCount'][$interval] > 0) {
                      $ratingFilter = substr($interval,0,1);
                    }
                    else {
                      $ratingFilter = "";
                    }
                    $url = 'href="'.add_query_params($all_review_url,array('rating'=>$ratingFilter)).'"';
                    ?>
                    <div class="starBar <?=$disableClass?>">
                        <div class="starC"><a <?=$url?>><?php echo $intervalDisplayName ?> star</a></div>
                        <div class="loadbar">
                            <div class="fillBar" style="width:<?php echo $percentage.'%'; ?>"></div>
                        </div>
                        <div class="starPrgrs"><?php echo $percentage; ?>%</div>
                    </div>
                    <?php
                }
                ?>
            </div>
        </div>
        <?php 
        $showAggreagtesByDifferentParams = false;
        foreach ($aggregateReviewsData['aggregateRating'] as $ratingName => $value) {
            if($value > 0){
                $showAggreagtesByDifferentParams = true;
                break;
            }
        }
        if($showAggreagtesByDifferentParams){
            ?>
            <div class="rvwRight">
                <div class="align-cntr">
                    <h2 class="ratingAll">Component Ratings <span>(Out of 5)</span></h2>
                    <?php 
                    foreach ($aggregateRatingDisplayOrder as $ratingName => $displayName) {
                        if($aggregateReviewsData['aggregateRating'][$ratingName] > 0){
                            ?>
                            <div class="starBar">
                                <div class="cRating"><?php echo number_format($aggregateReviewsData['aggregateRating'][$ratingName],1); ?></div>
                                <div class="componetText"><?php echo $displayName; ?></div>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
    <div class="allRvws">
        <div class="rvwImg"></div>
        <div class="getAllrvws"> All <span class="new-title"><?php echo $totalReviews; ?> reviews</span> have been published only after ensuring that the reviewers are <span class="new-title">bona fide students </span> of this college.</div>
    </div>
</div>