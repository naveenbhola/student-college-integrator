<?php if(count($courseReviews[$course->getId()]['reviews']) > 0) { ?>
    <div id="course-reviews" class="other-details-wrap clear-width course-reviews-section">
    <div class="clear-width" style="border-bottom:1px solid #ccc;">

    <h5 class="mb14 flLt" style="border:0; padding-bottom:0;">College Reviews </h5>
    <div class="flRt sorting-options">
    <p>
    Sort: <?php
        $sortCriteria = array("graduationYear" => array('name' => "Year of Graduation", "class" => "active"),
                              "freshness" => array('name' => "Recently Submitted", "class" => "active"),
                              "highestRated" => array('name' => "Highest Rating", "class" => "active"),
                              "lowestRated" => array('name' => "Lowest Rating", "class" => "active")
                        );
        $count = 1;
        $sortCriteriaSize = count($sortCriteria);
        foreach($sortCriteria as $name => $sortData) { 
            $active = '';
            if($courseReviewDefaultSort == $name) {
                $active = 'active';
            }
            ?>
                <span class="<?php echo $active;?>" data-sort="<?php echo $name;?>"><?php echo $sortData['name'];?></span>

        <?php 
            if($count < $sortCriteriaSize) {
                echo " | ";
            }
            $count++;
        } 
        ?>
        </p>
    </div>
    </div>
    <div class="course-collge-review-title clear-width" style="padding-top:10px;" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
        <div class="flLt avg-rating-title"><strong>Average Alumni Rating:</strong> </div>
        <div class="ranking-bg"><?php echo $courseReviews[$course->getId()]['overallAverageRating'];?><sub>/<?php echo $courseReviews[$course->getId()]['reviews'][0]['ratingParamCount'];?></sub></div>
        <meta itemprop="ratingValue" content="<?php echo $courseReviews[$course->getId()]['overallAverageRating'];?>">
        <meta itemprop="reviewCount" content="<?php echo $courseReviews[$course->getId()]['overallRecommendations'];?>">
        <?php if($courseReviews[$course->getId()]['overallRecommendations'] >= 5) { ?>
            <span class="flLt">|</span>
            <div class="recommended-title flLt">
                <i class="sprite-bg thumb-up-icon"></i>
               <strong><?php echo $courseReviews[$course->getId()]['overallRecommendations'];?> Students Recommend This Course</strong>
            </div>
        <?php } ?>
    </div>
    <?php $this->load->view('listing/national/widgets/courseReviewsContent'); ?>
    
    <?php $count = count($courseReviews[$course->getId()]['reviews']);?>
</div>
<?php
}
?>
<div id="overlayContainer" style=" background-color: #000000;display: none;left: 0;opacity: 0.4;position: absolute;top: 0;z-index: 1000;" ></div>
<div class="loader" id="loadingImage" style="position:absolute;display:none;z-index:9999;"><img src="/public/images/Loader2.GIF"> Loading</div>
<script>
    var totalCollegeReviewsCount = <?php echo ($count > 0) ? $count : 0;?>;
</script>
