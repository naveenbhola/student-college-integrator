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
    if($listing_type =='university' || $listing_type=='institute'){
      $reviewHeading = 'College'; 
    }   
    else{
      $reviewHeading = 'Course'; 
    }

    $showAggregateWidget = true;


    if(empty($aggregateReviewsData)){
      $showAggregateWidget = false;  
    }

    if(isset($isPaid) && $isPaid && $aggregateReviewsData['aggregateRating']['averageRating']<3.5){
       $showAggregateWidget = false;  
    }

?>

<div class="crs-widget listingTuple" id="reviews">
  <h2 class="head-L2"><?=$reviewHeading; ?> Reviews<span class="head-L5 pad-left4"> (Showing <?=$reviewShowing; if(!$allCoursePage){ echo ' of '.$totalReviews;} ?> review<?php if($totalReviews>1) echo 's'; ?>)</span></h2>
  <?php 
    if($showAggregateWidget){
        ?>
        <div class="group-card lcard rvw-crd">
            <?php $this->load->view('mobile_listing5/overallReviewRatingWidget'); ?>
        </div>
        <?php
    }
  ?>
  <div class="lcard art-crd snpchat">
  <?php
      $j = 0;
      foreach ($reviewsData as $reviewRow) {
        $j++;
        $lastChild ='';
        if($j == count($reviewsData)){
          $lastChild ='last-child';
        }
        $userDetails = array();
        $userDetails['userName'] = ($reviewRow['anonymousFlag']=='YES')?'Anonymous':ucwords(strtolower($reviewRow['reviewerDetails']['username']));

        if($reviewRow['yearOfGraduation'] && $courseInfo[$reviewRow['courseId']]['courseName']){
          $userDetails['batchInfo'] = " - Batch of ".$reviewRow['yearOfGraduation'];
        }
        else{
          $userDetails['batchInfo'] = "Batch of ".$reviewRow['yearOfGraduation'];
        }
        $allReviewUrl  = $allCoursePage ? $courseInfo[$reviewRow['courseId']]['course_review_url'] : $all_review_url;
        $showDescLen = 450;
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
            $totalSegmentslen = $totalSegmentsl=en + strlen($reviewSegments['Description']);
        }
        
        $minCharPerSection = 10;
        $showEllipses = '...';

        $ratingBar = $reviewRow['averageRating']*100/count($reviewRating[$reviewRow['id']]);
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

 <div class="group-card gap pad-off <?php echo $lastChild;?>">
      <div class="rvwv1Heading">
        <div>
          <div class="new_rating">
            <span class='rating-block'>
              <?php echo number_format($reviewRow['averageRating'], 1, '.', ''); ?>
              <i class="empty_stars starBg">
                <i style="width: <?php echo $ratingBar.'%'; ?>" class="full_starts starBg"></i>
              </i>
              <b class="icons bold_arw"></b>  
            </span>
            <span><?php echo nl2br(htmlentities($reviewRow['reviewTitle']));?></span>
              <div class="rating_popup">
               <div class="inline-rating">
                <?php
                    foreach ($aggregateRatingDisplayOrder as $ratingName => $displayName) {
                        foreach($reviewRating[$reviewRow['id']] as $ratingId => $rating){ 
                            if($ratingName == $crMasterMappingToName[$ratingId]){
                                $ratingBar = $rating*20;
                                ?>
                                <div class="table_row">
                                   <div class="table_cell">
                                     <p class="rating_label"><?php echo $displayName;?> </p>
                                   </div>
                                   <div class="table_cell">
                                      <div  class="loadbar"><div style="width: <?php echo $ratingBar.'%'; ?>" class="fillBar"></div></div>
                                      <b class="bar_value"><?php echo $rating; ?></b>
                                   </div>
                                </div>
                                <?php 

                                break; 
                            } 
                        } 
                    } 
                ?>  
               </div>
            </div>
          </div>
          <p class="byUser">by <span><?php echo $userDetails['userName']; ?></span>, <?php echo date('d M Y',strtotime($reviewRow['postedDate']));?> | <?php echo $courseInfo[$reviewRow['courseId']]['courseName'].$courseInfo[$reviewRow['courseId']]['courseNameSuffix'].$userDetails['batchInfo']; ?></p>
        </div>
      </div>
      <div class="rvwv1-h">
        <div class="tabcontentv1">
          <div class="tabv_1">
            <?php
                foreach ($reviewSegments as $reviewSegmentName => $reviewSegmentText) {

                    if($showDescLen>0){ ?>
                    <p>
                    <?php if($reviewSegmentName != 'Description'){ 
                            ?>
                            <strong class='rateHead'><?=$reviewSegmentName;?> :</strong> 
                            <?php 
                        } 
                        echo nl2br(htmlentities(substr($reviewSegmentText,0,$showDescLen)));
                        
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
                    <a class="readMoreLnk link-blue-small" ga-page="AllReviewPage_Mobile" ga-attr="read_more" ga-optlabel="click" href="<?php echo $allReviewUrl.'#id='.$reviewRow['id'].'&seqId='.$j;?>" >more</a>
                    <?php 
                }
            ?>
            </p>
          </div>
        </div>
      </div> 
    </div>   
        <?php } ?>
        <?php 
            if($totalReviews>=4 || $allCoursePage){
                ?>
                    <a class="btn-mob-ter" href="<?php echo $all_review_url;?>" ga-attr="<?=$GA_Tap_On_View_All;?>" id="reviews_count">View All <?php if(!$allCoursePage){ echo $totalReviews; }?> Review<?php if($allCoursePage || $totalReviews>1) echo 's'; ?></a>
                <?php 
            } 
        ?>
      </div>
    </div>


    <script>
        var totalReviews = '<?php echo $totalReviews;?>';
    </script>

