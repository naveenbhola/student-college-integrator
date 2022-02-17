<div class="rvw-h">
    <?php 
        if(!isset($totalElements)){
            $totalElements = $totalReviews;
        }
    ?>
    <?php if((isset($totalElements) && $totalElements!=0) || (isset($totalReviews) && $totalReviews!=0) ) { ?>
    <div class="rvwBlock">
        <div class="rvwLeft">
            <h2 class="ratingAll">Overall Rating <span>(Out of 5)</span></h2>
            <div class="rvwScore">
                <h3><?php echo number_format($aggregateReviewsData['aggregateRating']['averageRating'], 1, '.', '');?></h3>
                <div class="infrontRvws">
                    <i class="empty_stars">
                    <?php 
                    $percentage = round(($aggregateReviewsData['aggregateRating']['averageRating']/5 * 100));
                    ?>
                    <i class="full_starts" style="width:<?php echo $percentage.'%'; ?>"></i>
                    </i>
                </div>
                <div class="refrnceTxt">
                    Based on <?php echo $totalReviews; ?> Verified Review<?php echo $totalReviews > 1 ? 's':''; ?>
                </div>
            </div>
            <div class="rvwProgress">
                <?php 
                $intervalsDisplayOrder = $this->config->item("intervalsDisplayOrder");
                foreach ($intervalsDisplayOrder as $interval => $intervalDisplayName) {
                    $percentage = round(($aggregateReviewsData['intervalRatingCount'][$interval]/$aggregateReviewsData['totalCount']) * 100);
                    $disableClass='';
                    if($percentage == 0) {
                      $disableClass='disablefilter';
                    }
                    ?>
                    <div class="starBar <?=$disableClass?>">
                      <?php 
                        if($aggregateReviewsData['intervalRatingCount'][$interval] > 0) {
                          $ratingFilter = substr($interval,0,1);
                        }
                        else {
                          $ratingFilter = "";
                        }
                        $url = '';
                        if($showRatingFilterUrl) {
                          $url = 'href="'.add_query_params($all_review_url,array('rating'=>$ratingFilter)).'"';
                        }
                      ?>
                        <div class="starC rating-filter" rating="<?=$ratingFilter?>"><a <?=$url?>><?php echo $intervalDisplayName ?> star</a></div>
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
<?php } ?>
    <div class="allRvws">
        <div class="rvwImg"></div>
        <?php $reviewText1 = $allReviewsCount>1?' reviews':' review';?>
        <?php $reviewText2 = $totalElements>1?' reviews are':' review is';?>
        <?php echo (isset($totalElements) && isset($allReviewsCount) && $allReviewsCount!=0 && $totalElements!=0 && $allReviewsCount>$totalElements)?'<div class="getAllrvws">Out of '.$allReviewsCount.' published'.$reviewText1.', <span class="new-title">'.$totalElements.$reviewText2.'  verified.</span> The <span class="verified-tag"><i class="icon-verified-tag"></i>Verified</span>  <span class="new-title">badge</span> indicates that the reviewer\'s details have been verified by Shiksha, and reviewers are <span class="new-title">bona fide students</span> of this college. These reviews and ratings have been given by students. Shiksha does not endorsed the same.</div>':'' ?>
        <?php echo ((isset($totalElements) && isset($allReviewsCount) && $allReviewsCount==$totalElements && $totalElements!=0))?
        '<div class="getAllrvws">All <span class="new-title">'.$totalElements.$reviewText2.'  verified.</span> The <span class="verified-tag"><i class="icon-verified-tag"></i>Verified</span>  <span class="new-title">badge</span> indicates that the reviewer\'s details have been verified by Shiksha, and reviewers are <span class="new-title">bona fide students</span> of this college. These reviews and ratings have been given by students. Shiksha does not endorsed the same.</div>':''?>
        <?php echo (isset($totalElements) && !isset($allReviewsCount) && $totalElements!=0)?
        '<div class="getAllrvws"> All <span class="new-title">'.$totalReviews.' reviews</span> have been published only after ensuring that the reviewers are <span class="new-title">bona fide students </span> of this college. These reviews and ratings have been given by students. Shiksha does not endorse the same.</div>':''?>
        <?php echo isset($totalElements) && isset($allReviewsCount) && $totalElements==0?'
        <div class="getAllrvws">The reviewer\'s details of the following '.$allReviewsCount.' reviews have not been verified yet. These reviews and ratings have been given by students. Shiksha does not endorsed the same.</div>':''?>
        
    </div>
</div>
