
<?php
if(count($reviewData['results']) == 0)  { ?>
  <div class="colgRvwHP revewNav whtBx2">
    <p class="clr"></p>
  <div class="revwListBx revwListBx2 whtBx bordRdus3px">
    No Reviews found. 
  </div>
  </div>
<?php }
  
    if(!isset($count))
    {
      $count = 3;
    }
    if(!isset($PageNo))
    {
      $PageNo = 1;
    }
    $iteration = $reviewData['totalCollegeCards'] - ($count * ($PageNo - 1)); // need to be change (1 - 1) is ($PageNo - 1)
    
    foreach($reviewData['results'] as $courseId => $reviewDataResult) {
        $loopCount = 0;
     ?>  
            <div class="colgRvwSlidrBx bordRdus3px">

            <div class="colgRvwNew-p1"> 
                <div class="rv_title" id="<?php echo 'institute_'.$courseId; ?>">
        <a href="<?php echo $reviewDataResult['instituteUrl'].'/reviews';?>"><?php echo $reviewDataResult['instituteName'];?></a>
                </div> 
                <p class="rv_add" id="<?php echo 'city_'.$courseId; ?>"><?php echo $reviewDataResult['cityName'];?></p> 
                <p class="rv_course" id="<?php echo 'course_'.$courseId ?>"><a href="<?php echo $reviewDataResult['instituteUrl'].'/reviews?course='.$courseId?>"><?php echo $reviewDataResult['courseName']?></a></p> 
            </div>
      <?php
      $width = (300 * $reviewDataResult['totalReviews'])."px";
      ?>
            <div class="colgRvwSlidrBx-inner" style='width:<?php echo $width?>;' stream='<?=$stream?>' baseCourse='<?=$baseCourse?>' educationType='<?=$educationType?>' substream='<?=$substream?>' courseId='<?php echo $courseId?>' totalReviews='<?php echo $reviewDataResult['totalReviews']?>'> <!--  Code for slider id need to be placed id='1' for demo-->
        <!-- ist box starts-->
    <?php 
$reviewsToDisplay = 5;
if($reviewDataResult['totalReviews']<4){
        $reviewsToDisplay = $reviewDataResult['totalReviews'];
}
for($i=0 ;$i<$reviewsToDisplay; $i++){   //need to be changed
//for($i=0 ;$i<$reviewDataResult['totalReviews']; $i++){   //need to be changed
    if($loopCount == 0){
        $yearGrad = $reviewDataResult['yearOfGraduation'];
         $username = $reviewData['reviewerDetails'][$reviewDataResult['reviewId']][0]['username'];
         $URL['permURL'] = $reviewDataResult['review_seo_url'];
         if(strlen($username)>25){
            $username = substr($username, 0,22)." ...";
         }
         $username = ($reviewDataResult['anonymousFlag'] == 'NO')?$username:'Anonymous';
         $username_id = "username_".$reviewDataResult['reviewId'];
         $year_id = "year_".$reviewDataResult['reviewId'];
         $rating_id = "rating_".$reviewDataResult['reviewId'];
         $recommend_id = "recommend_".$reviewDataResult['reviewId'];
         $posted_id = "posted_".$reviewDataResult['reviewId'];
         $helpful_id = "helpful_".$reviewDataResult['reviewId'];
         $review_seo_url = $reviewDataResult['review_seo_url'];
         $review_seo_title = $reviewDataResult['review_seo_title'];
         
        }
    else{
        $username = $reviewData['reviewerDetails'][$reviewDataResult['reviewId']][1]['username'];
        if(strlen($username)>25){
            $username = substr($username, 0,22)." ...";
         }
        $yearGrad = $reviewDataResult['nextYearOfGraduation'];
        $username = ($reviewDataResult['nextAnonymousFlag'] == 'NO')?$username:'Anonymous';
        $username_id = "username_".$reviewDataResult['nextReviewId'];
        $year_id = "year_".$reviewDataResult['nextReviewId'];
        $rating_id = "rating_".$reviewDataResult['nextReviewId'];
        $recommend_id = "recommend_".$reviewDataResult['nextReviewId'];
        $posted_id = "posted_".$reviewDataResult['nextReviewId'];
        $helpful_id = "helpful_".$reviewDataResult['nextReviewId'];
        $review_seo_url = $reviewDataResult['nextReviewSeoUrl'];
        $review_seo_title = $reviewDataResult['nextReviewSeoTitle'];
    }
    ?>   
          <div class="colgRvwHP revewNav whtBx2" style='padding: 0' id='<?php echo $courseId.'_review_'.$i?>'>
              <div class="revwListBx revwListBx2 whtBx bordRdus3px">
                    
          <p class="clr"></p>
            <div class="rv_midd"><span class="rv_midd1" id="<?php echo $username_id?>" style = "word-wrap: break-word;width:100%;"> <?php echo $username?> </span><span class="rv_midd2" id='<?php echo $year_id?>'>Class of <?php echo $yearGrad?></span>
              <p class="clr"></p>
          <ul class="rv_nav">
            <li>
          <div class="rv_ratng" > Rating:<span id="<?php echo $rating_id?>">
          <?php if($loopCount == 0)
          {
            if(strpos(round($reviewDataResult['reviewAvgRating'],1),'.')){echo round($reviewDataResult['reviewAvgRating'],1);}else{echo round($reviewDataResult['reviewAvgRating'],1).'.0';}
          }
          else
          {
            if(strpos(round($reviewDataResult['nextRating'],1),'.')){echo round($reviewDataResult['nextRating'],1);}else{echo round($reviewDataResult['nextRating'],1).'.0';}
          }
          ?>  
          <b>/<?php echo $reviewDataResult['ratingParamCount'];?></b></span>
          </div>
          <i style='margin-left:7px;'></i>
            </li>
            <li style="border-right: 0px;" id="<?php echo $recommend_id ?>">
              
              <?php
              if($loopCount == 0){
                if($reviewDataResult['recommendCollegeFlag'] == 'YES'){
               ?>
                           <i class="sprite thumb-up-icon" style="margin-top:1px;vertical-align: middle"></i><span style="color: #878787;font-size: 11px;vertical-align: middle; display: inline-block;">Recommended<span>
                         </span></span>
                         <?php
                         }else{
                         ?>
                           <i class="sprite thumb-dwn-icon" style="margin-top:1px;vertical-align: middle"></i><span style="color: #878787;font-size: 11px;vertical-align: middle; display: inline-block;">Not Recommended<span>
                         </span></span>
                         <?php
                         }
              }
              else
              {
               if($reviewDataResult['nextRecommendFlag'] == 'YES'){
?>
            <i class="sprite thumb-up-icon" style="margin-top:1px;vertical-align: middle"></i><span style="color: #878787;font-size: 11px;vertical-align: middle; display: inline-block;">Recommended<span>
          </span></span>
          <?php
          }else{
          ?>
            <i class="sprite thumb-dwn-icon" style="margin-top:1px;vertical-align: middle"></i><span style="color: #878787;font-size: 11px;vertical-align: middle; display: inline-block;">Not Recommended<span>
          </span></span>
          <?php
          }
               
              }
              ?>
            </li>
            </ul>
       </div>
        <?php $maxStringSize = 140;
          if($reviewDataResult['placementDescription']){
                  $review = substr($reviewDataResult['placementDescription'],0,$maxStringSize);
                }else if($reviewDataResult['infraDescription']){
                  $review = substr($reviewDataResult['infraDescription'],0,$maxStringSize);
                }else if($reviewDataResult['facultyDescription']){
                  $review = substr($reviewDataResult['facultyDescription'],0,$maxStringSize);
                }else{
                  $review = substr($reviewDataResult['reviewDescription'],0,$maxStringSize);
                }

                if($reviewDataResult['nextTilePlacement']){
                  $review_next = substr($reviewDataResult['nextTilePlacement'],0,$maxStringSize);
                }else if($reviewDataResult['nextTileInfra']){
                  $review_next = substr($reviewDataResult['nextTileInfra'],0,$maxStringSize);
                }else if($reviewDataResult['nextTileFaculty']){
                  $review_next = substr($reviewDataResult['nextTileFaculty'],0,$maxStringSize);
                }else{
                  $review_next = substr($reviewDataResult['nextTileDescription'],0,$maxStringSize);
                }
        ?>
        
        <?php
         if($loopCount == 0){
          ?>
        
                    <div class="rv_desc" id="readMore147">
            <?php echo $review; ?>
            <?php if($reviewDataResult['placementDescription'] || $review != $reviewDataResult['reviewDescription']){ ?>
            <a href="#readMoreReviewLayer" data-transition="slide" data-rel="dialog" data-inline="true" onclick=populateReadMoreLayer("<?= $reviewDataResult['reviewId']?>","<?=$courseId ?>");> ...more</a>
              <?php } ?>
        </div>
        <?php
       
        }
        else{
          ?>
          <div class="rv_desc" id="readMore147">
            <?php echo $review_next; ?>
            <?php if($reviewDataResult['nextTilePlacement'] || $review_next != $reviewDataResult['nextTileDescription']){ ?>
            <a href="#readMoreReviewLayer" data-transition="slide" data-rel="dialog" data-inline="true" onclick=populateReadMoreLayer("<?= $reviewDataResult['nextReviewId']?>","<?=$courseId ?>");> ...more</a>
              <?php } ?>
            </div>
          <?php
        }
        
        ?>
                    <div class="rv_desc" id="readMoreFull147" style="display: none;">Great campus with great crowd. Library is pretty good, particularly access to journals etc. Something or the other keeps happening to keep you engaged always. Campus sleeps late, all the more fun. Guest lectures and events keep happening on a regular basis to keep you updated with the buzz in the world at large. Not too many places to hang out around the campus and the city is too far.</div>
                    <div class="rv_hlpful" id="<?php echo $helpful_id?>">
                        <!--div class="rv_hlpfulYES">
                        Is this review helpful?  <a>YES</a>
                        </div-->
      <div class="rv_hlpfulYES">
      
      <?php
        
          if($loopCount == 0 ){
      
         if($validateuser && ($userSessionData[$sessionId][$reviewDataResult['reviewId']]['userId'] == $userId) && ($userSessionData[$sessionId][$reviewDataResult['reviewId']]['isSetHelpfulFlag'] == 'YES' || $userSessionData[$sessionId][$reviewDataResult['reviewId']]['isSetHelpfulFlag'] == 'NO')){

              $showHelpfulText = false;
         } else if($validateuser == 'false' && ($userSessionData[$sessionId][$reviewDataResult['reviewId']]['sessionId'] == $sessionId) && ($userSessionData[$sessionId][$reviewDataResult['reviewId']]['isSetHelpfulFlag'] == 'YES' || $userSessionData[$sessionId][$reviewDataResult['reviewId']]['isSetHelpfulFlag'] == 'NO')){
            echo "inside this";   
              $showHelpfulText = false;
         } else {
             
              $showHelpfulText = true;
         }
          ?>
          <?php if($showHelpfulText){ ?> 
         <p id="rv_helpful_text_<?php echo $reviewDataResult['reviewId']; ?>" style="color: #c2c0c0; float: left; font-size: 12px; line-height: 22px;">Is this review helpful? <a id="helpfulFlag" name="helpfulFlag" value="Yes" style="color: #006fa2; font-weight:bold " onclick="storeHelpfulFlag(<?php echo $reviewDataResult['reviewId']; ?>,this,<?php echo $userId?>); return false;" href="">YES</a><a id="notHelpfulFlag" name="notHelpfulFlag" value="No" style="color: #006fa2; font-weight:bold " onclick="storeHelpfulFlag(<?=$reviewDataResult['reviewId']; ?>,this,<?=$userId?>); return false;" href="">NO</a></p>
         <p id="rv_thank_text_<?php echo $reviewDataResult['reviewId']; ?>" style="color: #c2c0c0; float: left; font-size: 12px; line-height: 22px; display:none;">Thank you for your vote</p>
          <?php } else { ?>
         <p id="rv_reviewed_text_<?php echo $reviewDataResult['reviewId']; ?>" style="color: #c2c0c0; float: left; font-size: 12px; line-height: 22px;">Thanks, You have already voted.</p>
          <?php }
          
          }
        // for 2nd user
        
          else{
            if($validateuser && ($userSessionData[$sessionId][$reviewDataResult['nextReviewId']]['nextUserId'] == $userId) && ($userSessionData[$sessionId][$reviewDataResult['nextReviewId']]['isSetHelpfulFlag'] == 'YES' || $userSessionData[$sessionId][$reviewDataResult['nextReviewId']]['isSetHelpfulFlag'] == 'NO')){
              
              $showHelpfulText = false;
         } else if($validateuser == 'false' && ($userSessionData[$sessionId][$reviewDataResult['nextReviewId']]['sessionId'] == $sessionId) && ($userSessionData[$sessionId][$reviewDataResult['nextReviewId']]['isSetHelpfulFlag'] == 'YES' || $userSessionData[$sessionId][$reviewDataResult['nextReviewId']]['isSetHelpfulFlag'] == 'NO')){ 
              $showHelpfulText = false;
         } else {
              $showHelpfulText = true;
         }
          ?>
          <?php if($showHelpfulText){ ?> 

         <p id="rv_helpful_text_<?php echo $reviewDataResult['nextReviewId']; ?>" style="color: #c2c0c0; float: left; font-size: 12px; line-height: 22px;">Is this review helpful? <a id="helpfulFlag" name="helpfulFlag" value="Yes" style="color: #006fa2; font-weight:bold " onclick="storeHelpfulFlag(<?php echo $reviewDataResult['nextReviewId']; ?>,this,<?php echo $userId?>); return false;" href="">YES</a><a id="notHelpfulFlag" name="notHelpfulFlag" value="No" style="color: #006fa2; font-weight:bold " onclick="storeHelpfulFlag(<?=$reviewDataResult['reviewId']; ?>,this,<?=$userId?>); return false;" href="">NO</a></p>
         <p id="rv_thank_text_<?php echo $reviewDataResult['nextReviewId']; ?>" style="color: #c2c0c0; float: left; font-size: 12px; line-height: 22px; display:none;">Thank you for your vote</p>
          <?php } else { ?>
         <p id="rv_reviewed_text_<?php echo $reviewDataResult['nextReviewId']; ?>" style="color: #c2c0c0; float: left; font-size: 12px; line-height: 22px;">Thanks, You have already voted.</p>
          <?php }
          }
          ?>
            
            </div>
                    </div>
          
        <!-- <a id= < ?='all_reviews_'.$courseId?> style="margin-right:15px;" class="rv_gryBtn1 ui-link" href="< ?=$reviewDataResult['courseUrl'].'?section=collegeReview'; ?>">
          All reviews of < ?php if($reviewDataResult['abbreviation'] != ''){echo $reviewDataResult['abbreviation'];}else{ echo 'this'.' '.'course' ;} ?>
        </a> -->
                </div> <!-- Inner Ends -->
                </div>
        <!-- 2nd box starts-->
        
        <!-- 3rd -->
        <?php
        $loopCount++;
        } ?>
                <p class="clr"></p>
            </div>
        
            
        </div>
<?php
$iteration--;
}
?>

<script>
  $populateReadMorelayerFlag = true;
    //


<?php if(isset($ajaxCall)) {?>
$('.colgRvwSlidrBx-inner').unbind(); // just for click events
 var widgetHandler = new CollegeReviewSlider(".colgRvwSlidrBx-inner");
 widgetHandler.bindCollegeReviewWidget();


  <?php } ?>

</script>


