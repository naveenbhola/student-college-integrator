<div id="allContentTuple" >
    <div class="loaderAjax" id="allContentTupleLoader" style="display: none;background: rgba(0, 0, 0, 0.180392);"></div>
<?php
    $GA_Tap_On_View_More = 'VIEW_MORE_REVIEW';

//    if(!empty($aggregateReviewsData)){
        if($totalElements==0){
          $className = 'no-pad';
        }
        ?>

        <div class="group-card pad-off rvw-crd <?php echo $className;?>">
          <?php if($totalElements!=0) { ?>
            <div class="rvw-h btm-brdr">
                <h2 class="new-title">Average rating of this <?php echo ucfirst($fetchListingContentType); ?></h2>
            </div>
          <?php } ?>
            <?php $this->load->view('nationalInstitute/InstitutePage/overallReviewRatingWidget', array('totalReviews' => $totalElements));?>
        </div>
      <?php
  //  }
    ?>
    <div class="group-card pad-off rvw-crd table-rvws">
    <?php
    if(!empty($placementsTopicTagsIds) && count($placementsTopicTagsIds) > 0) {
      ?>
        <div class="rvw-h btm-brdr">
          <h2 class="new-title">Read reviews that mention</h2>
       </div>
       <div class="rvw-h">
        <?php foreach ($placementsTopicTagsIds as $tagId) {
          if($placementsTopicTagsName[$tagId]){ ?>
          <div class="rvw-bubbels <?php if($selectedTagId == $tagId){ echo "active";}?>" ga-page="AllReviewPage_PlacementTopics_Desktop" ga-attr="Placement_Topics" ga-optlabel="click"  id="tagId_<?php echo ($tagId);?>" onclick="$j('#selectedTagId').val(<?php echo $tagId;?>);updateTags(this,'<?php echo $tagId;?>');" ><?php echo ($placementsTopicTagsName[$tagId]); ?></div>
          <?php } }?>
       </div>
        <?php }
    if(!empty($sortingOptions) && count($sortingOptions) > 0) {
      ?>
        <div class="tableFlat">
            <div class="sortBy">Sort By :</div>
            <?php foreach($sortingOptions as $sortKey => $sortValue ){ ?>    
                <div ga-attr="<?php echo strtoupper(str_replace(' ','',$sortValue))."_".$GA_Tap_On_Sort;?>" class = "<?php echo strtoupper($selectedSortOption) == strtoupper($sortValue) ?  "active": " ";?>" onclick="updateReviewsBySort(this,'<?php echo $sortValue;?>');"> <?php echo $sortValue;?><span class="arwEntity">&#8595;</span></div>
            <?php } ?>  
        </div>
      <?php }?>
      </div>
  <?php 
    $k=0;
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
        $userDetails = array();
        $userDetails['userName'] = ($reviewRow['anonymousFlag']=='YES')?'Anonymous':ucwords(strtolower($reviewRow['reviewerDetails']['username']));

        $reviewClientId = $courseInfo[$reviewRow['courseId']]['clientId'];

        if($reviewRow['yearOfGraduation'] && $courseInfo[$reviewRow['courseId']]['courseName']){
          $userDetails['batchInfo'] = " - Batch of ".$reviewRow['yearOfGraduation'];
        }
        else{
          $userDetails['batchInfo'] = "Batch of ".$reviewRow['yearOfGraduation'];
        }
        $reviewTitle = trim($reviewRow['reviewTitle']);
        $courseName = $reviewRow['courseName'];

        $showDescLen = 650;
        $minCharPerSection = 90;
        $totalSegmentslen = 0;
        $reviewSegments = array();
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

        if($totalSegmentslen>$showDescLen){
            $showEllipses = '...';
        } 
        $ratingBar = $reviewRow['averageRating']*100/count($reviewRating[$reviewRow['id']]);
?>
    <?php if($unverifiedReviewCounter==1 && $totalElements!=0){ 
    ?>
    <div class="rvw-sec-sepr"><strong>The reviewer's details of the following have not been verified yet.</strong></div>
    <?php } ?>
    <div class="group-card pad-off rvw-crd" id="reviewCard_<?=$reviewRow['id'];?>">
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
        <p class="byUser">
          <?php if($verifiedReviewFlag){ ?>
          <span class="verified-tag"><i class="icon-verified-tag"></i>Verified</span> 
        <?php } ?>

          <span><?php echo $userDetails['userName']; ?></span>, <?php echo date('d M Y',strtotime($reviewRow['postedDate']));?> |  <?php echo $courseInfo[$reviewRow['courseId']]['courseName'].$courseInfo[$reviewRow['courseId']]['courseNameSuffix'].$userDetails['batchInfo']; ?></p>
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
    <div class="tabcontentv1" id="descSection_<?=$reviewRow['id']?>">
        <div class="tabv_1">
            <?php  $this->load->helper('html');
                foreach ($reviewSegments as $reviewSegmentName => $reviewSegmentText) {
                    if($showDescLen>0){ 
                      $data = html_entity_decode($reviewSegmentText);?>
                    <p>
                      <?php  if($reviewSegmentName != 'Description'){ 
                            ?>
                            <strong><?=$reviewSegmentName;?> :</strong> 
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
                    <br>
                    <a href="javascript:void(0);" class="link" ga-attr="<?=$GA_Tap_On_View_More;?>" onclick="checkShowResponse(this,'<?=$reviewRow['id']?>','<?=$reviewRow['courseId']?>')" hideReco='true' hideRecoLayer = 'true' customCallBack="callBackRegistarionReadMore" customActionType = 'Read_Course_Review' cta-type="read_course_review" reviewId='<?=$reviewRow['id']?>' id="moreDesc_<?=$reviewRow['id']?>">Read More</a>  
                    <?php
                }
                else {
            ?>
            <div class="rvw-h fdbck-sec">
                <?php
                    if($validateuser && ($userSessionData[$sessionId][$reviewRow['id']]['userId'] == $userId) && (($userSessionData[$sessionId][$reviewRow['id']]['isSetHelpfulFlag'] == 'YES') || ($userSessionData[$sessionId][$reviewRow['id']]['isSetHelpfulFlag'] == 'NO'))) {
                        $showHelpfulText = false;
                    } else if($validateuser == 'false' && ($userSessionData[$sessionId][$reviewRow['id']]['sessionId'] == $sessionId) && (($userSessionData[$sessionId][$reviewRow['id']]['isSetHelpfulFlag'] == 'YES') || ($userSessionData[$sessionId][$reviewRow['id']]['isSetHelpfulFlag'] == 'NO'))) {
                        $showHelpfulText = false;
                    } else {
                        $showHelpfulText = true;
                    }
                  
                ?>
                <?php if(!(isset($validateuser[0]['userid']) && $validateuser[0]['userid'] == $reviewClientId)) {?>
                    <?php if($showHelpfulText){ ?>
                        <div id = "rv_helpful_text_<?=$reviewRow['id']; ?>">
                            <span class="fdback">was this review helpful</span>
                            <span>
                                <a id="helpfulFlag" name="helpfulFlag" value="YES"  onclick="storeHelpfulFlag(<?=$reviewRow['id']; ?>,this,<?=$userId?>); return false;" href="javascript:void(0);" ga-attr="YES_REVIEW_HELPFUL">YES</a>
                                <a id="notHelpfulFlag" name="notHelpfulFlag" value="NO" onclick="storeHelpfulFlag(<?=$reviewRow['id']; ?>,this,<?=$userId?>); return false;" href="javascript:void(0);" ga-attr="NO_REVIEW_HELPFUL">NO</a>
                            </span>
                        </div>
                        <span id="rv_thank_text_<?=$reviewRow['id']; ?>" style="color: #666; float: left; font-size: 12px; line-height: 0px; display:none;">
                            Thank you for your vote
                        </span>
                    <?php } else { ?>
                        <span id="rv_reviewed_text_<?=$reviewRow['id']; ?>" style="color: #666; float: left; font-size: 12px; line-height: 0px;">
                            Thanks, You have already voted.
                        </span>
                    <?php } } ?>
                    <?php
                    if(isset($validateuser[0]['userid']) && $validateuser[0]['userid'] == $reviewClientId && empty($reviewReplies[$reviewRow['id']]))
                        { ?>
                        <a class="btn-secondary top-b reply-w" id="reply_b_<?=$reviewRow['id'];?>" target="_blank" onclick="showReviewReplyBox(this,'<?=$reviewRow['id'];?>')">Reply</a>
                    <?php } ?>
                    
            </div>            
            <?php } ?>
          </p>
        </div>
    </div>
    <?php if($remainingLen>0) { ?>
    
    <div class="tabcontentv1" id="fullDescSection_<?=$reviewRow['id']?>" style="display:none;">
        <div>
            <?php foreach ($reviewSegments as $reviewSegmentName => $reviewSegmentText) {
                if($reviewSegmentName == 'Description'){
                ?>
                     <p class="rvw-titl"><?php echo nl2br(html_entity_decode($reviewSegmentText));?></p>
                <?php 
                }
                else{?>
                <p class="rvw-titl"><strong><?php echo $reviewSegmentName;?> :</strong> <?php echo nl2br(html_entity_decode($reviewSegmentText));?></p>
            <?php   
                }
            }
            ?>
             <div class="rvw-h fdbck-sec">
                <?php
                    if($validateuser && ($userSessionData[$sessionId][$reviewRow['id']]['userId'] == $userId) && (($userSessionData[$sessionId][$reviewRow['id']]['isSetHelpfulFlag'] == 'YES') || ($userSessionData[$sessionId][$reviewRow['id']]['isSetHelpfulFlag'] == 'NO'))) {
                        $showHelpfulText = false;
                    } else if($validateuser == 'false' && ($userSessionData[$sessionId][$reviewRow['id']]['sessionId'] == $sessionId) && (($userSessionData[$sessionId][$reviewRow['id']]['isSetHelpfulFlag'] == 'YES') || ($userSessionData[$sessionId][$reviewRow['id']]['isSetHelpfulFlag'] == 'NO'))) {
                        $showHelpfulText = false;
                    } else {
                        $showHelpfulText = true;
                    }
                  
                ?>
                <?php if(!(isset($validateuser[0]['userid']) && $validateuser[0]['userid'] == $reviewClientId)) {?>
                    <?php if($showHelpfulText){ ?>
                        <div id = "rv_helpful_text_<?=$reviewRow['id']; ?>">
                            <span class="fdback">was this review helpful</span>
                            <span>
                                <a id="helpfulFlag" name="helpfulFlag" value="YES"  onclick="storeHelpfulFlag(<?=$reviewRow['id']; ?>,this,<?=$userId?>); return false;" href="javascript:void(0);" ga-attr="YES_REVIEW_HELPFUL">YES</a><b>|</b>
                                <a id="notHelpfulFlag" name="notHelpfulFlag" value="NO" onclick="storeHelpfulFlag(<?=$reviewRow['id']; ?>,this,<?=$userId?>); return false;" href="javascript:void(0);" ga-attr="NO_REVIEW_HELPFUL">NO</a>
                            </span>
                        </div>
                        <span id="rv_thank_text_<?=$reviewRow['id']; ?>" style="color: #666; float: left; font-size: 12px; line-height: 0px; display:none;">
                            Thank you for your vote
                        </span>
                    <?php } else { ?>
                        <span id="rv_reviewed_text_<?=$reviewRow['id']; ?>" style="color: #666; float: left; font-size: 12px; line-height: 0px;">
                            Thanks, You have already voted.
                        </span>
                    <?php } } ?>
                    <?php
                    if(isset($validateuser[0]['userid']) && $validateuser[0]['userid'] == $reviewClientId && empty($reviewReplies[$reviewRow['id']]))
                        { ?>
                        <a class="btn-secondary top-b reply-w" id="reply_b_<?=$reviewRow['id'];?>" target="_blank" onclick="showReviewReplyBox(this,'<?=$reviewRow['id'];?>')">Reply</a>
                    <?php } ?>
            </div>            
        </div>      
     </div>

     <?php } ?>

         <?php
         $displayText = 'display:none';
          if(!empty($reviewReplies[$reviewRow['id']])) {
                $displayText = 'display:block';
             }
         ?>
             <div class="new-rply-content">
                 <div class="rply-txt rvw-titl" id="replyText_<?=$reviewRow['id'];?>" style="<?=$displayText;?>">
                     <strong><?=$reviewRow['institeteName'];?> replied :</strong>
                     <p id="replyText_C_<?=$reviewRow['id'];?>"><?php if(!empty($reviewReplies[$reviewRow['id']])){
                         echo nl2br_Shiksha(sanitizeAnAMessageText($reviewReplies[$reviewRow['id']],'reply'));
                     }
                     ?></p>
                 </div>
             </div>
     <?php 
     if(isset($validateuser[0]['userid']) && $validateuser[0]['userid'] == $reviewClientId && empty($reviewReplies[$reviewRow['id']]))
     {
     ?> 
     <div class="new-form">
         <form id="review_c_<?=$reviewRow['id'];?>" action="" accept-charset="utf-8" method="post" novalidate="novalidate" name="clientReview">
             <div class="ans-block" id="review_cli_<?=$reviewRow['id'];?>" style="display: none;">
                 <p class="txt-count" style="display:block;top:11px" id="entityTxtCounter<?=$childDetail['msgId']?>">Characters <span id="review_text_<?=$reviewRow['id'];?>_counter">0</span>/1000</p>
                 <textarea placeholder="Type your reply" validate="validateStr" minlength=50 maxlength=1000  caption="reply" onkeypress="handleCharacterInTextField(event,true);" onkeyup="autoGrowField(this,300);textKey(this)" id="review_text_<?=$reviewRow['id'];?>" required="true" onpaste="handlePastedTextInTextField('review_text_<?=$reviewRow['id'];?>',true);" spellcheck="false" style="z-index: auto; position: relative; line-height: normal; font-size: 13.3333px; transition: none; background: transparent !important;"></textarea>
             <div>
                 <p class="err0r-msg" id="review_text_<?=$reviewRow['id'];?>_error"></p>
             </div>
             <div class="btns-col">
                 <span class="right-box">
                     <a class="exit-btn" href="javascript:void(0);" onclick="hideReviewReplyBox(this,'<?=$reviewRow['id'];?>')">Cancel</a>
                     <a class="prime-btn" href="javascript:void(0);" id="reviewPost_<?=$reviewRow['id'];?>" onclick="postReviewReply('<?=$reviewRow['id'];?>','<?=$reviewRow['courseId'];?>','<?=$reviewClientId;?>')" actionperformed="commentPosting">Post</a>
                 </span>
                 <p class="clr"></p>
             </div>
             </div>
         </form>
     </div>
     <?php } ?>

  </div>
</div>
<?php
        $k++;
        if($k==3)
        {
          $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerPlace' => 'C_C2','bannerType'=>"content"));
          $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerPlace' => 'C_C2_2','bannerType'=>"content"));

        }
        if($k==6)
        {
          $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerPlace' => 'C_C1','bannerType'=>"content"));
          $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerPlace' => 'C_C1_2','bannerType'=>"content"));
        }
    }
      if($k<3 || $k<6)
      {
        $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerPlace' => 'C_C2','bannerType'=>"content"));
        $this->load->view('dfp/dfpCommonHtmlBanner',array('bannerPlace' => 'C_C2_2','bannerType'=>"content"));
        
      }
      
?>
</div>
