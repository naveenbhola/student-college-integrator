  <?php
		$GA_Tap_On_View_More = 'VIEW_MORE_REVIEW';
        $GA_Tap_On_Rating = 'VIEW_REVIEW_RATING';
        $maxReviewsCount = count($reviewsData);
      $j = 0;

        if($totalElements==0){
          $className = 'no-pad';
        }
        ?>
        <div class="lcard art-crd <?php echo $className;?>">
          <?php if($totalElements!=0) { ?>
          <div class="rvwv1">
            <h2 class="">Average rating of this <?php echo ucfirst($fetchListingContentType); ?></h2>
          </div>
          <?php } ?>
          <?php $this->load->view('mobile_listing5/overallReviewRatingWidget',array('totalReviews' => $totalElements)); ?>
        </div>
<?php
     if(!empty($aggregateReviewsData)){
        if(!empty($placementsTopicTagsIds) && count($placementsTopicTagsIds) > 0) { ?>
        <div class="lcard art-crd table-rvws pdf_displayNone">
          <div class="rvwv1">
          <h2>Read reviews that mention</h2>
          </div>
           <div class="rvw-h" id="limitedTags">
            <?php $index =0; foreach ($placementsTopicTagsIds as $tagId) {
                
                if($placementsTopicTagsName[$tagId]){ ?>
                   <div class="rvw-bubbels <?php if($selectedTagId == $tagId){ echo "active";}?>" ga-page="AllReviewPage_PlacementTopics_Mobile" ga-attr="Placement_Topics" ga-optlabel="click" id="tagId_<?php echo ($tagId);?>" ><?php echo ($placementsTopicTagsName[$tagId]); ?></div>

             <?php $index++; 
                if($index==12){
                  break;
                }
              } 
           } if($index == 12 &&sizeof($placementsTopicTagsIds) > 12 ) {?> 
               <a href="javascript:void(0);" id="ViewMoreTopics" class="_link">View All</a><?php } ?>
          </div>

          <div class="rvw-h hid" id="allTags">
            <?php foreach ($placementsTopicTagsIds as $tagId) {
                
                if($placementsTopicTagsName[$tagId]){ ?>
                   <div class="rvw-bubbels <?php if($selectedTagId == $tagId){ echo "active";}?>" ga-page="AllReviewPage_PlacementTopics_Mobile" ga-attr="Placement_Topics" ga-optlabel="click" id="tagId_<?php echo ($tagId);?>" ><?php echo ($placementsTopicTagsName[$tagId]); ?></div>

             <?php }}?>      
          </div>

    </div>
        <?php
      }}
      $flag=0;
      $verifiedReviewsCount = $totalElements;
    
      if(empty($reviewsData) && !empty($unverifiedReviewsData)){
        $finalData = $unverifiedReviewsData;  
      }
      if(!empty($reviewsData) && empty($unverifiedReviewsData)){
        $finalData = $reviewsData;  
      }
      if(!empty($reviewsData) && !empty($unverifiedReviewsData)){
        $finalData = array_merge($reviewsData, $unverifiedReviewsData);        
      }
      $unverifiedReviewCounter = 0;
      foreach ($finalData as $reviewRow) {
        $verifiedReviewFlag = false;
	if($reviewRow['status']=='published'){
          $verifiedReviewFlag = true;
        }else{
          $unverifiedReviewCounter++;
        }
        $j++;
        if($j==4 && !$pdf){
    $this->load->view('mcommon5/dfpBannerView',array("bannerPosition" => "X_LAA"));
        $this->load->view('mcommon5/dfpBannerView',array("bannerPosition" => "X_LAA1"));
        $flag=1;
  }
  if($j==7 && !$pdf){
    $this->load->view('mcommon5/dfpBannerView',array("bannerPosition" => "X_AON"));
        $this->load->view('mcommon5/dfpBannerView',array("bannerPosition" => "X_AON1"));
  }
        $lastChild ='';
        if($j == count($reviewsData)){
          $lastChild ='last-child';
        }
        $userDetails = array();
        $userDetails['userName'] = ($reviewRow['anonymousFlag']=='YES')?'Anonymous':ucwords(strtolower($reviewRow['reviewerDetails']['username']));

        $reviewClientId = $courseInfo[$reviewRow['courseId']]['clientId'];

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
            $totalSegmentslen = $totalSegmentslen + strlen($reviewSegments['Description']);
        }
        
        $minCharPerSection = 10;
        $showEllipses = '...';

        $ratingBar = $reviewRow['averageRating']*100/count($reviewRating[$reviewRow['id']]);
        ?>
        <?php if($unverifiedReviewCounter==1 && $totalElements!=0){ 
    ?>
    <div class="rvw-sec-sepr"><strong>The reviewer's details of the following have not been verified yet.</strong></div>
    <?php } ?>
    <div class="lcard art-crd <?php echo $lastChild;?>" id="reviewCard_<?=$reviewRow['id'];?>" >
         <div class="rvwv1Heading">
           <div>
             <div class="new_rating">
               <span class='rating-block'>
                 <?php echo number_format($reviewRow['averageRating'], 1, '.', ''); ?>
                 <i class="empty_stars starBg">
                   <i style="width: <?php echo $ratingBar.'%'; ?>" class="full_starts starBg"></i>
                 </i>
                 <b class="icons bold_arw pdf_displayNone"></b>  
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
             <p class="byUser">
              <?php if($verifiedReviewFlag){ ?>
              <span class="verified-tag"><i class="icon-verified-tag"></i>Verified</span>
            <?php } ?>
               <span><?php echo $userDetails['userName']; ?></span>, <?php echo date('d M Y',strtotime($reviewRow['postedDate']));?> | <?php echo $courseInfo[$reviewRow['courseId']]['courseName'].$courseInfo[$reviewRow['courseId']]['courseNameSuffix'].$userDetails['batchInfo']; ?></p>
           </div>
         </div>
         <div class="rvwv1-h" id="descSection_<?=$reviewRow['id']?>" >
           <div class="tabcontentv1">
             <div class="tabv_1">
               <?php
                   foreach ($reviewSegments as $reviewSegmentName => $reviewSegmentText) {  
                       if($pdf){
                        $showDescLen = $totalSegmentslen;
                       }
                       if($showDescLen>0){
                       $data = html_entity_decode($reviewSegmentText);?>
                       <p>
                       <?php if($reviewSegmentName != 'Description'){ 
                               ?>
                               <strong class='rateHead'><?=$reviewSegmentName;?> :</strong> 
                               <?php 
                           } 
                           if($selectedTagId>0 && $reviewSegmentName == 'Placements'){
                             echo getTextFromHtml($data,$showDescLen);
                           }
                           else{
                              echo nl2br(htmlentities(substr($reviewSegmentText,0,$showDescLen)));
                           } 
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
                       <a class="readMoreLnk link-blue-small" ga-page="AllReviewPage_Mobile" ga-attr="read_more" ga-optlabel="click" onclick="checkShowResponse(this,'<?=$reviewRow['id']?>','<?=$reviewRow['courseId']?>')" cta-type="read_course_review" id="moreDesc_<?=$reviewRow['id']?>">more</a>
                       <?php 
                   }
               else{
               ?>

                  <div class="rv_hlpful pdf_displayNone" style='margin-top: 10px;'>
                   <?php
                        if( is_array($validateuser) && ($userSessionData[$sessionId][$reviewRow['id']]['userId'] == $userId) && (($userSessionData[$sessionId][$reviewRow['id']]['isSetHelpfulFlag'] == 'YES')|| ($userSessionData[$sessionId][$reviewRow['id']]['isSetHelpfulFlag'] == 'NO'))){
                             $showHelpfulText = false;
                        } else if($validateuser == 'false' && ($userSessionData[$sessionId][$reviewRow['id']]['sessionId'] == $sessionId) && (($userSessionData[$sessionId][$reviewRow['id']]['isSetHelpfulFlag'] == 'YES')|| ($userSessionData[$sessionId][$reviewRow['id']]['isSetHelpfulFlag'] == 'NO'))){
                             $showHelpfulText = false;
                        } else {
                             $showHelpfulText = true;
                        }
                   ?>

                   <?php if($showHelpfulText){ ?>
                        <p id="rv_helpful_text_<?=$reviewRow['id']; ?>" style="color: #111; float: left; font-size: 12px; line-height: 22px;">Was this review helpful? <a id="helpfulFlag" name="helpfulFlag" value="Yes" style="color: #008489; font-weight:bold " onclick="storeHelpfulFlag(<?=$reviewRow['id']; ?>,this,<?=$userId?>); return false;" href="">YES</a>
                        <a id="notHelpfulFlag" name="notHelpfulFlag" value="No" style="color: #008489; font-weight:bold " onclick="storeHelpfulFlag(<?=$reviewRow['id'];?>,this,<?=$userId?>); return false;" href="">NO</a></p>
                        <p id="rv_thank_text_<?=$reviewRow['id']; ?>" style="color: #111; float: left; font-size: 12px; line-height: 22px; display:none;">Thank you for your vote</p>
                   <?php } else { ?>
                        <p id="rv_reviewed_text_<?=$reviewRow['id']; ?>" style="color: #111; float: left; font-size: 12px; line-height: 22px;">Thanks, You have already voted.</p>
                   <?php } ?>
                </div>   
              <?php } ?>                     
               </p>
             </div>
           </div>
         </div>

         <div class="rvwv1-h" id="fullDescSection_<?=$reviewRow['id']?>" style="display:none;"'>
           <div class="tabcontentv1">
             <div class="tabv_1">
               <?php
                   foreach ($reviewSegments as $reviewSegmentName => $reviewSegmentText) { ?>
                       <p>
                        <?php if($reviewSegmentName == 'Description'){
                           getTextFromHtml($reviewSegmentText);
                           ?>
                                <p><?php echo nl2br(html_entity_decode($reviewSegmentText));?></p>
                           <?php 
                           }
                           else{?>
                           <p class='rateHead'><strong><?php echo $reviewSegmentName;?> :</strong> <?php echo nl2br(html_entity_decode($reviewSegmentText));?></p>
                       <?php   
                           }
                      
                   } 
               ?>
                  <div class="rv_hlpful" style='margin-top: 10px;'>
                   <?php
                        if(is_array($validateuser) && ($userSessionData[$sessionId][$reviewRow['id']]['userId'] == $userId) && (($userSessionData[$sessionId][$reviewRow['id']]['isSetHelpfulFlag'] == 'YES')|| ($userSessionData[$sessionId][$reviewRow['id']]['isSetHelpfulFlag'] == 'NO'))){
                             $showHelpfulText = false;
                        } else if($validateuser == 'false' && ($userSessionData[$sessionId][$reviewRow['id']]['sessionId'] == $sessionId) && (($userSessionData[$sessionId][$reviewRow['id']]['isSetHelpfulFlag'] == 'YES')|| ($userSessionData[$sessionId][$reviewRow['id']]['isSetHelpfulFlag'] == 'NO'))){
                             $showHelpfulText = false;
                        } else {
                             $showHelpfulText = true;
                        }
                   ?>
                   <?php if($showHelpfulText){ ?>
                        <p id="rv_helpful_text_<?=$reviewRow['id']; ?>" style="color: #111; float: left; font-size: 12px; line-height: 22px;">Was this review helpful? <a id="helpfulFlag" name="helpfulFlag" value="Yes" style="color: #008489; font-weight:bold " onclick="storeHelpfulFlag(<?=$reviewRow['id']; ?>,this,<?=$userId?>); return false;" href="">YES</a><a id="notHelpfulFlag" name="notHelpfulFlag" value="No" style="color: #008489; font-weight:bold " onclick="storeHelpfulFlag(<?=$reviewRow['id']; ?>,this,<?=$userId?>); return false;" href="">NO</a></p>
                        <p id="rv_thank_text_<?=$reviewRow['id']; ?>" style="color: #111; float: left; font-size: 12px; line-height: 22px; display:none;">Thank you for your vote</p>
                   <?php } else { ?>
                        <p id="rv_reviewed_text_<?=$reviewRow['id']; ?>" style="color: #111; float: left; font-size: 12px; line-height: 22px;">Thanks, You have already voted.</p>
                   <?php } ?>
                   
                </div>   
               </p>
             </div>
           </div>
         </div> 

       </div>   
           <?php }

           if($flag==0 && !$pdf){
  $this->load->view('mcommon5/dfpBannerView',array("bannerPosition" => "X_LAA"));
        $this->load->view('mcommon5/dfpBannerView',array("bannerPosition" => "X_LAA1"));
}


            ?>
        
<input type="hidden" id="allReviewIds" name="allReviewIds" value='<?php echo json_encode($allReviews);?>'>
<div class="dtl-review review_layer" style="display:none;">
    <div class="new-container panel-pad">
        <div  class="review-layer-cont">
            <div id="reviewDetailLayer"></div>
            <div class="new-pgntn">
                 <div class="">       
                     <a class="prev" id="prevReviewButton" href="javascript:void(0);" ga-attr="VIEW_PREVIOUS_REVIEW">Previous<i class="n-sprite"></i></a>
                       <p class="no-of"><span id="rowCount"></span>of <?php echo $totalElements;?></p>
                    <a class="next" id="nextReviewButton" href="javascript:void(0);" ga-attr="VIEW_NEXT_REVIEW">Next<i class="n-sprite"></i></a>
                 </div>
            </div>
        </div>
    </div>
</div>

<?php
if(count($reviewsData)<=10 && !$pdf){
        echo $recoWidget;
}
?>












