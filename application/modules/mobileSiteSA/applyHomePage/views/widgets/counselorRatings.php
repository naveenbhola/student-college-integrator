<?php
?>
<div class="cnslr-info clearfix cnslr-mrgn" data-enhance="false">
    <h2>Overall Rating <span class="cnlsr-rtg"><?php echo ($counselorRatings['overAllRating']-intval($counselorRatings['overAllRating'])==0?intval($counselorRatings['overAllRating']):$counselorRatings['overAllRating']); ?>/10</span></h2>
    <ul>
        <li>
            <label><h3>Knowledge rating</h3> <span><?php echo ($counselorRatings['knowledgeRating']-intval($counselorRatings['knowledgeRating'])==0?intval($counselorRatings['knowledgeRating']):$counselorRatings['knowledgeRating']); ?>/10</span></label>
            <p class="rtng-txt">Indicates the knowledge of the counselor</p>
        </li>
        <li>
            <label><h3>Responsiveness rating</h3><span><?php echo ($counselorRatings['reachabilityRating']-intval($counselorRatings['reachabilityRating'])==0?intval($counselorRatings['reachabilityRating']):$counselorRatings['reachabilityRating']); ?>/10</span></label>
            <p class="rtng-txt">Indicates if counselor got back to the student as promised</p>
        </li>
    </ul>
    <h3 class="exp-title noBrd">Recommendation Score</h3>
    <div class="prg-box">
        <div class="progress-bar"><?php echo $recommendationRatingSVG; ?></div>
        <div class="bar-detail"><span>Indicates the likelihood of students to recommend our services to their friends and family</span></div>
        <span class="svgLabel" <?php echo ($recommendationRating==100?'style="left:8px;"':''); ?>><span class="perc" <?php echo ($recommendationRating==100?'style="font-size:18px;"':''); ?>><?php echo $recommendationRating; ?></span>%</span>
    </div>
</div>
<div class="rev-box">
    <h2 class="exp-title rvw-title"><?php echo $counselorReviews['totalReviewCount']; ?> Reviews</h2>
    <div>
        <i class="cnslr-sprite vrfy-icn"></i>
        <p class="vrfy-txt">Verified - All Reviews collected from actual students, who used Shiksha Study Abroad Counseling Service</p>
    </div>
</div>