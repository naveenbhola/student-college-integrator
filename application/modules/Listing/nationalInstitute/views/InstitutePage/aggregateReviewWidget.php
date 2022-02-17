<?php 
    $GA_TAp_On_View_More = 'VIEW_MORE_REVIEW';
    $GA_Tap_On_View_All = 'VIEW_ALL_REVIEWS';
    if($listing_type == 'course') {
        $reviewsHeading = "Course Reviews";
    }
    else{
        $reviewsHeading = "Student & Alumni Reviews";
    }
    $showAggregateWidget = true;


    if(empty($aggregateReviewsData)){
      $showAggregateWidget = false;  
    }

    if(isset($isPaid) && $isPaid && $aggregateReviewsData['aggregateRating']['averageRating']<3.5){
       $showAggregateWidget = false;  
    }
?>   

<div class="new-row listingTuple" id='review'>
  <div class="group-card no__pad gap" >
      <h2 class="head-1 gap"><?php echo $reviewsHeading;?> <span class="para-6">(Showing <?=$reviewShowing; if(!$allCoursePage){ echo ' of '.$totalReviews;} ?> review<?php if($totalReviews>1) echo 's'; ?>)</span></h2>
      <?php 
        if($showAggregateWidget){
            ?>
            <div class="group-card pad-off rvw-crd">
                <?php $this->load->view('nationalInstitute/InstitutePage/overallReviewRatingWidget'); ?>
            </div>
            <?php
        }
      ?>
            <?php
                $countReview = 0;
                foreach ($reviewsData as $reviewRow) {
                  $countReview++;
                  $childClass = '';

                  if($countReview == count($reviewsData)){
                    $childClass ='lst-chld';
                  }
                  if($countReview == 1){
                    $childClass = 'frst-chld'; 
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
                  $showDescLen = 650;
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

                  $ratingBar = $reviewRow['averageRating']*100/count($reviewRating[$reviewRow['id']]);
                  ?>

              <div class="pad-off rvw-crd <?php echo $childClass; ?>">
                <div class="rvwv1Heading">
                  <div>
                    <div class="new_rating">
                      <span> <?php echo number_format($reviewRow['averageRating'], 1, '.', ''); ?>
                        <i class="empty_stars starBg">
                          <i style="width: <?php echo $ratingBar.'%'; ?>" class="full_starts starBg"></i>
                        </i>
                      </span> 
                      <?php echo $reviewRow['reviewTitle']; ?>
                    </div>  
                  <p class="byUser">by <span><?php echo $userDetails['userName']; ?></span>, <?php echo date('d M Y',strtotime($reviewRow['postedDate']));?> |  <?php echo $courseInfo[$reviewRow['courseId']]['courseName'].$courseInfo[$reviewRow['courseId']]['courseNameSuffix'].$userDetails['batchInfo']; ?></p>
                </div>
              </div>
              <div class="rvwv1-h">
                <div class="tabv1" id="sliding-tabs">
                <?php
                    foreach ($aggregateRatingDisplayOrder as $ratingName => $displayName) {
                        foreach($reviewRating[$reviewRow['id']] as $ratingId => $rating){ 
                            if($ratingName == $crMasterMappingToName[$ratingId]){
                                ?>
                                <div class="<?php echo $displayName;?>">
                                    <?php echo $displayName.' ';?><strong class="ratingVal"><?php echo $rating; ?></strong><span class="maxRating">/5</span>
                                </div>
                                <?php 

                                break; 
                            } 
                        } 
                    } 
                ?>  
              </div>
              <div class="tabcontentv1">
                  <div class="tabv_1">
                      <?php
                          foreach ($reviewSegments as $reviewSegmentName => $reviewSegmentText) {
                              if($showDescLen>0){ ?>
                              <p>
                                <?php  if($reviewSegmentName != 'Description'){ 
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
                              <a href="<?php echo $allReviewUrl.'#'.$reviewRow['id'];?>" class="readMoreLnk" target="_blank" >Read More</a>
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
                <div class="alter-div align-center">
                    <a href="<?php echo $all_review_url;?>" class="button button--secondary arw_link btn-medium" ga-attr="<?=$GA_Tap_On_View_All?>" target="_blank" id="review_count">View All <?php if(!$allCoursePage){ echo $totalReviews; }?> Review<?php if($allCoursePage || $totalReviews>1) echo 's'; ?></a>
                </div>
                <?php 
            } 
        ?>
  </div>  
</div>


<script>
    var totalReviews = '<?php echo $totalReviews;?>';
</script>