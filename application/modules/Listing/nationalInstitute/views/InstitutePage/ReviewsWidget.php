<?php 
    $GA_TAp_On_View_More = 'VIEW_MORE_REVIEW';
    $GA_Tap_On_View_All = 'VIEW_ALL_REVIEWS';
?>
<div class="new-row listingTuple" id='review'>
    <div class="group-card no__pad gap" >
        <?php 
            if($listing_type == 'course') {
                $reviewsHeading = "Course Reviews";
            }
            else{
                $reviewsHeading = "Student & Alumni Reviews";
            }
        ?>
        <h2 class="head-1 gap"><?php echo $reviewsHeading;?> <span class="para-6">(Showing 3 of <?=$totalReviews;?> reviews)</span></h2>
        <div class="new__reviews col-md-12 equalblockheight">
            <?php
                foreach ($reviewsData as $reviewRow) {
                
                    $userDetails = array();
                    $userName = ($reviewRow['anonymousFlag']=='YES')?'Anonymous':ucwords(strtolower($reviewRow['reviewerDetails']['username']));
                    if(strlen($userName)>9){
                        $name = substr($userName,0,7).'...';
                    }else{
                        $name = $userName;
                    }
                    $userDetails[] = '<span class="name">'.$name.'</span>';
                    if($reviewRow['yearOfGraduation'])
                        $userDetails[] = "Batch ".$reviewRow['yearOfGraduation'];
                
                    $showDescLen = 270;
                    
                    
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
                
                    $minCharPerSection = 10;
                    $showEllipses = '...';
                
                    ?>

                    <div class="col-md-6 block card-clickable" ga-attr="<?=$GA_TAp_On_View_More?>">
                        <div class="panel-group" >
                            <div class="panel-head">
                                <div class="rvw-dtls">
                                    <div class="btch-dtl">
                                        <span class="author"><?php echo implode("", $userDetails);?></span>
                                        <div class="rating-scr">
                                            <strong class="rating__txt">Rating</strong><?php echo number_format($reviewRow['averageRating'], 1, '.', '');?><span class="out-of">/<i>5</i></span>
                                            <div class="rating-star">
                                                <ol class="rating-ol">
                                                    <?php foreach($reviewRating[$reviewRow['id']] as $desc=>$rating){?>
                                                    <li>
                                                        <label><?php echo $desc;?></label>
                                                        <p>
                                                            <?php for($i = 0;$i<$rating;$i++){?>
                                                            <span class="sprite-str star-fill"></span>
                                                            <?php } for($i = 0;$i<5-$rating;$i++){?>
                                                            <span class="sprite-str star"></span>
                                                            <?php } ?>
                                                        </p>
                                                    </li>
                                                    <?php } ?>
                                                </ol>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clr"></div>
                                </div>
                            </div>
                            <div class="panel-body clear">
                                <div class="rvw-h">
                                    <p class="rvw-titl">
                                        <?php
                                            foreach ($reviewSegments as $reviewSegmentName => $reviewSegmentText) {
                                                if($showDescLen>0){
                                                    if($reviewSegmentName != 'Description'){ 
                                                        ?>
                                                        <strong><?=$reviewSegmentName;?> :</strong> 
                                                        <?php 
                                                    } 
                                                    echo htmlentities(substr($reviewSegmentText,0,$showDescLen));
                                                    
                                                    $remainingLen =  $totalSegmentslen-$showDescLen;
                                                    $showDescLen = (int)($showDescLen) - strlen($reviewSegmentText);
                                                    if($showDescLen>0 && $showDescLen<$minCharPerSection && $remainingLen>$minCharPerSection){
                                                        $showDescLen = $minCharPerSection;
                                                    }  
                                                }
                                            } 
                                            if($remainingLen>0){
                                                echo $showEllipses;
                                                ?>
                                                <br>
                                                <a href="<?php echo $all_review_url.'#'.$reviewRow['id'];?>" class="link redirectLink" target="_blank" >Read More</a>
                                                <?php 
                                            }
                                        ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            ?>
        </div>
        <?php 
            if($totalReviews>2){
                ?>
                <div class="alter-div align-center">
                    <a href="<?php echo $all_review_url;?>" class="btn-secondary arr__after btn-medium" ga-attr="<?=$GA_Tap_On_View_All?>" target="_blank" id="review_count">View All <?=$totalReviews;?> Reviews</a>
                </div>
                <?php 
            } 
        ?>
    </div>
</div>
<script>
    var totalReviews = '<?php echo $totalReviews;?>';
</script>