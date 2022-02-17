<?php 
if($pageType =='reviews' && !empty($aggregateReviewsData['intervalRatingCount']) && $totalElements>0) { ?>
<div class="dropdown-primary no-mrgn review-filter-div starFilter">
    <span class="option-slctd"><?php 
        $ratingFilterDisplayMapping = $this->config->item('ratingFilterDisplayMapping');
        echo $ratingFilterDisplayMapping[$selectedFilterRating];
    ?></span>
    <span class="icon"></span>
    <ul class="dropdown-nav rating-filter">
        <?php 
        foreach ($ratingFilterDisplayMapping as $interval => $intervalDisplayName) {
            if(array_key_exists($interval.'-'.($interval+1), $aggregateReviewsData['intervalRatingCount']) && $aggregateReviewsData['intervalRatingCount'][$interval.'-'.($interval+1)] == 0) {
                continue;
                $ratingInterval = '';
            }
            else {
                $ratingInterval = $interval;
            }
            ?>
            <li><a href="javascript:void(0)" rating="<?=$ratingInterval?>"><?=$intervalDisplayName?></a></li>
        <?php } ?>
    </ul>
</div>    
<?php } ?>
