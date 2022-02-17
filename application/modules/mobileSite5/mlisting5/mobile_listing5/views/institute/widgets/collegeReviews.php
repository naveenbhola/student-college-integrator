<?php 
    if ($listing_type == "course") {
        $GA_Tap_On_See_More = 'VIEW_MORE_REVIEW_COURSEDETAIL_MOBILE';
        $GA_Tap_On_View_All = 'VIEW_ALL_REVIEW_COURSEDETAIL_MOBILE';
        $GA_Tap_On_Rating = 'VIEW_REVIEW_RATING_COURSEDETAIL_MOBILE';
    }
    else
    {
        $GA_Tap_On_See_More = 'VIEW_MORE_REVIEW';
        $GA_Tap_On_View_All = 'VIEW_ALL_REVIEWS';
        $GA_Tap_On_Rating = 'VIEW_REVIEW_RATING';
    }
?>
<div class="crs-widget listingTuple" id="reviews">
    <h2 class="head-L2"><?=$reviewHeading?> Reviews<span class="head-L5 pad-left4">(Showing 2 of <?=$totalReviews;?> reviews)</span></h2>
    <div class="lcard art-crd snpchat">
        <?php
            $j = 0;
            foreach ($reviewsData as $reviewRow) {
                $j = $j+1;
                $userName = ($reviewRow['anonymousFlag']=='YES')?'Anonymous':ucwords(strtolower($reviewRow['reviewerDetails']['username']));
                if($reviewRow['yearOfGraduation'])
                    $batch = "Batch of ".$reviewRow['yearOfGraduation'];
            
                $reviewTitle = trim($reviewRow['reviewTitle']);
            
                $reviewSegments = array();
                $totalSegmentslen = 0;
                if(trim($reviewRow['placementDescription'])){
                    $reviewSegments['Placements'] = $reviewRow['placementDescription'];
                    $totalSegmentslen = $totalSegmentslen + strlen($reviewSegments['Placements']);
                }
                if(trim($reviewRow['infraDescription'])){
                    $reviewSegments['Infrastructure'] = $reviewRow['infraDescription'];
                    $totalSegmentslen = $totalSegmentslen + strlen($reviewSegments['Infrastructure']);
                }
                if(trim($reviewRow['facultyDescription'])){
                    $reviewSegments['Faculty'] = $reviewRow['facultyDescription'];
                    $totalSegmentslen = $totalSegmentslen + strlen($reviewSegments['Faculty']);
                }
                if(trim($reviewRow['reviewDescription']) && ($reviewRow['placementDescription'] != '' || $reviewRow['infraDescription'] != '' || $reviewRow['placementDescription'] != '')){
                    $reviewSegments['Other'] = $reviewRow['reviewDescription'];
                    $totalSegmentslen = $totalSegmentslen + strlen($reviewSegments['Other']);
                }else{
                    $reviewSegments['Description'] = $reviewRow['reviewDescription'];
                    $totalSegmentslen = $totalSegmentslen + strlen($reviewSegments['Description']);
                }
                
                $showDescLen = 250;
                $minCharPerSection = 40;
                $showEllipses = '...';
                ?>
                <script type="application/ld+json">
                    {
                       "@context":"http://schema.org/",
                       "@type":"Review",
                       "itemReviewed":{
                          "@type":"<?php echo $listing_type == 'course' ? 'Course':'CollegeOrUniversity' ?>",
                          "name":"<?php echo $listingName; ?>",
                          "url":"<?php echo $listingUrl; ?>"
                          <?php 
                            if($listing_type == 'course'){
                                ?>
                                ,"description":"<?php echo $listingName.' at '.$instituteName; ?>",
                                "provider":{
                                    "@type":"CollegeOrUniversity",
                                    "name": "<?php echo $instituteName; ?>",
                                    "url": "<?php echo $instituteUrl; ?>"
                                }
                                <?php
                            }
                          ?>
                       },
                       "reviewRating":{
                          "@type":"Rating",
                          "ratingValue":<?php echo number_format($reviewRow['averageRating'], 1, '.', '');?>
                       },
                       "author":{
                          "@type":"Person",
                          "name":"<?php echo ucwords(strtolower($reviewRow['reviewerDetails']['username'])); ?>"
                       },
                       <?php 
                            if(!empty($reviewRow['reviewTitle'])){
                                ?>
                                "name":"<?php echo htmlentities($reviewRow['reviewTitle']); ?>",
                                <?php
                            }
                       ?>
                       "reviewBody":
                            "<?php 
                                foreach ($reviewSegments as $reviewSegmentName => $reviewSegmentText) {
                                    echo htmlentities($reviewSegmentText);
                                }
                            ?>",
                       "publisher":{
                          "@type":"Organization",
                          "name":"Shiksha"
                       }
                    }                        
                </script>
                <div class="revw-card">
                    <p class="head-L2"><?=$userName;?> | <span class="para-L3"><?=$batch;?></span></p>
                    <div class="para-l3 rel">
                        <span class="rat-label">Rating:</span>
                        <span class="rat-col" ga-attr="<?=$GA_Tap_On_Rating;?>" onclick ="showRatingLayer('<?php echo $reviewRow['id'];?>');" id="ratingtxt_<?php echo $reviewRow['id'];?>"><?php echo number_format($reviewRow['averageRating'], 1, '.', '');?>/<span class="total">5</span><i class="open-tick"></i></span>
                        <span class="rat-sep"> | </span>
                        <?php if($reviewRow['recommendCollegeFlag'] == 'YES'){?>
                        <span class="rco-tag"><i class="green-ico"></i>Recommended</span>
                        <?php }else{?>
                        <span class="rco-tag"><i class="red-ico"></i>Not Recommended</span>
                        <?php } ?>
                        <div class="rating-col reviewfix" style="display:none" id="rating-col_<?php echo $reviewRow['id'];?>">
                            <ol>
                                <?php 
                                    foreach($reviewRating[$reviewRow['id']] as $desc=>$rating){
                                        ?>
                                        <li>
                                            <label><?php echo $desc;?></label>
                                            <p class="star-r">
                                                <span class="star-rating">
                                                <?php for($i = 0;$i<$rating;$i++){?>
                                                <span class="rating-primary full"></span>
                                                <?php } for($i = 0;$i<5-$rating;$i++){?>
                                                <span class="rating-primary none"></span>
                                                <?php } ?>
                                                </span>
                                            </p>
                                        </li>
                                        <?php 
                                    } 
                                ?>
                            </ol>
                        </div>
                    </div>
                    <?php 
                        if($reviewTitle){
                            ?>
                            <h2 class="titl-rvw"><?php echo nl2br(htmlentities($reviewTitle));?></h2>
                            <?php 
                        } 
                    ?>
                    <p class="revw-info">
                        <?php 
                            foreach ($reviewSegments as $reviewSegmentName => $reviewSegmentText) {
                                if($showDescLen>0){
                                    if($reviewSegmentName != 'Description'){
                                        ?>
                                        <strong><?php echo $reviewSegmentName;?>: </strong>
                                        <?php
                                    }
                                    echo nl2br(htmlentities(substr($reviewSegmentText,0,$showDescLen)));

                                    $remainingLen =  $totalSegmentslen-$showDescLen;
                                    $showDescLen = $showDescLen - strlen($reviewSegmentText);
                                    if($showDescLen>0 && $showDescLen<$minCharPerSection && $remainingLen>$minCharPerSection){
                                       $showDescLen = $minCharPerSection;
                                    }
                                }
                            }
                            if($remainingLen>0){
                                echo $showEllipses;
                                ?>
                                <a class="link-blue-small" href="<?php echo $all_review_url.'#id='.$reviewRow['id'].'&seqId='.$j;?>" ga-attr="<?=$GA_Tap_On_See_More;?>">more</a>
                                <?php 
                            }
                        ?>
                    </p>
                </div>
                <?php 
            } 
        ?>
        <?php 
            if($totalReviews>2){
                ?>
                <a class="btn-mob-ter" href="<?php echo $all_review_url;?>" ga-attr="<?=$GA_Tap_On_View_All;?>" id="reviews_count">View all <?=$totalReviews?> reviews </a>
                <?php 
            }
        ?>
    </div>
</div>
<script>
    var totalReviews = '<?php echo $totalReviews;?>';
</script>