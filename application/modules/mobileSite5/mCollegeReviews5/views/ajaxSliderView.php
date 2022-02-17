<?php
foreach($reviewData['results'] as $courseId => $reviewDataResult) {
			 
			  ?>
			  
<div class="colgRvwHP revewNav whtBx2" style='padding: 0;margin-right: 0px;'>
		          <div class="revwListBx revwListBx2 whtBx bordRdus3px">
                    
			    <p class="clr"></p>
			    <?php
	
			     $username = $reviewData['reviewerDetails'][$reviewDataResult['reviewId']]['username'];
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
			    ?>
			      <div class="rv_midd"><span class="rv_midd1" id="<?=$username_id?>" style = "word-wrap: break-word;width:100%;"><?=$username?> </span><span class="rv_midd2" id='<?=$year_id?>'>Class of <?=$reviewDataResult['yearOfGraduation'];?></span>
			        <p class="clr"></p>
				  <ul class="rv_nav">
				    <li>
					<div class="rv_ratng" > Rating:<span id="<?=$rating_id?>">
					<?php if(strpos(round($reviewDataResult['reviewAvgRating'],1),'.')){echo round($reviewDataResult['reviewAvgRating'],1);}else{echo round($reviewDataResult['reviewAvgRating'],1).'.0';}?>	
					<b>/5</b></span>
					</div>
					<i style='margin-left:7px;'></i>
				    </li>
				    <li style="border-right: 0px;" id="<?=$recommend_id ?>">
				      <?php if($reviewDataResult['recommendCollegeFlag'] == 'YES'){
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
			      ?>
				      
				    </li>
			      </ul>
			 </div>
			  <?php $maxStringSize = 120;
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
		    <?
		    $loopCount++;
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

                    <div class="rv_hlpful" id='<?=$helpful_id?>'>
			<div class="rv_hlpfulYES" >
			
			<?php

				 if($validateuser && ($userSessionData[$sessionId][$reviewDataResult['reviewId']]['userId'] == $userId) && ($userSessionData[$sessionId][$reviewDataResult['reviewId']]['isSetHelpfulFlag'] == 'Yes' || $userSessionData[$sessionId][$reviewDataResult['reviewId']]['isSetHelpfulFlag'] == 'No')){
					    $showHelpfulText = false;
				 } else if($validateuser == 'false' && ($userSessionData[$sessionId][$reviewDataResult['reviewId']]['sessionId'] == $sessionId) && ($userSessionData[$sessionId][$reviewDataResult['reviewId']]['isSetHelpfulFlag'] == 'Yes' || $userSessionData[$sessionId][$reviewDataResult['reviewId']]['isSetHelpfulFlag'] == 'No')){
					    $showHelpfulText = false;
				 } else {
					    $showHelpfulText = true;
				 }
		      ?>
		      <?php if($showHelpfulText){ ?> 
				 <p id="rv_helpful_text_<?=$reviewDataResult['reviewId']; ?>" style="color: #c2c0c0; float: left; font-size: 12px; line-height: 22px;">Is this review helpful? <a id="helpfulFlag" name="helpfulFlag" value="Yes" style="color: #006fa2; font-weight:bold " onclick="storeHelpfulFlag(<?php echo $reviewDataResult['reviewId']; ?>,this,<?php echo $userId?>); return false;" href="">YES</a><a id="notHelpfulFlag" name="notHelpfulFlag" value="No" style="color: #006fa2; font-weight:bold " onclick="storeHelpfulFlag(<?=$reviewDataResult['reviewId']; ?>,this,<?=$userId?>); return false;" href="">NO</a></p>
				 <p id="rv_thank_text_<?=$reviewDataResult['reviewId']; ?>" style="color: #c2c0c0; float: left; font-size: 12px; line-height: 22px; display:none;">Thank you for your vote</p>
		      <?php } else { ?>
				 <p id="rv_reviewed_text_<?=$reviewDataResult['reviewId']; ?>" style="color: #c2c0c0; float: left; font-size: 12px; line-height: 22px;">Thanks, You have already voted.</p>
		      <?php } ?>
		      
          	
            </div>
      </div>

                    </div>
          
		    <!-- <a style="margin-right:15px;" class="rv_gryBtn1 ui-link" href="< ?=$reviewDataResult['courseUrl'].'?section=collegeReview'; ?>">
		    	All reviews of < ?php if($reviewDataResult['abbreviation'] != ''){echo $reviewDataResult['abbreviation'];}else{ echo 'this'.' '.'course' ;} ?>
		    </a> -->
                </div>
			  <?php
}
?>
