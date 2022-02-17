
<?php foreach($reviewData['results'] as $reviewDataResult) {
		$URL['permURL'] = $reviewDataResult['review_seo_url'];
	?>
           <div class="revwListBx2 whtBx bordRdus3px" id="reviewNo<?=$reviewDataResult['reviewId']?>">
              <div class="revwListBxLft">
                <p class="rv_title"><a href="<?=$reviewDataResult['instituteUrl'].'/reviews'; ?>"><?=$reviewDataResult['instituteName'];?></a></p>
                <p class="rv_add"><?=$reviewDataResult['cityName'] ;?></p>
                <p class="rv_course"><a href="<?=$reviewDataResult['instituteUrl'].'/reviews?course='.$courseId; ?>"><?=$reviewDataResult['courseName'] ;?></a></p>
		
	    
	       <?php $username = $reviewData['reviewerDetails'][$reviewDataResult['reviewId']]['username']?>
               <?php if($orderOfReview == 'latest'){?>
	       
		<p class="clr"></p>
		</div>
		      <div class="revwListBxRgt">
				 <div class="rv_midd"> <span class="rv_midd1" style="max-width:218px; word-wrap:break-word">
				 <?php if($reviewDataResult['anonymousFlag'] == 'NO'){?> <?=$username?> <?php } else {?>
				 Anonymous<?php }?>
				 </span><span class="rv_midd2">Class of <?=$reviewDataResult['yearOfGraduation'];?></span>
				 <p class="clr"></p>
				 <ul class="rv_nav">
					    <li>
						       <div class="rv_ratng"> Rating:<span><?php if(strpos(round($reviewDataResult['reviewAvgRating'],1),'.')){echo round($reviewDataResult['reviewAvgRating'] ,1);}else{echo round($reviewDataResult['reviewAvgRating'],1).'.0';}?><b>/5</b></span></div><i></i>
					    </li>
			  <?php }else{ ?>
			  <div class="rv_ratng" style="margin-top:7px;"> Rating:<span><?php if(strpos(round($reviewDataResult['ratings'],1),'.')){echo round($reviewDataResult['ratings'],1);}else{echo round($reviewDataResult['ratings'],1).'.0';}?><b>/5</b></span></div>
			   <?php } ?>
			   
	    
		      
	    <?php if($orderOfReview == 'latest'){ ?>
					    <li style="border-right: 0px;">
					      <?php if($reviewDataResult['recommendCollegeFlag'] == 'YES'){ ?>
						 <i class="sprite-bg thumb-up-icon" style="margin-top:1px;vertical-align: middle"></i><span style="color: #878787;font-size: 12px;vertical-align: middle; display: inline-block;">Recommended<span>
					      <?php }else{ ?>
						 <i class="sprite-bg thumb-dwn-icon" style="margin-top: 5px;vertical-align: middle"></i><span style="color: #878787;font-size: 12px;vertical-align: middle; display: inline-block;">Not Recommended</span>
					      <?php } ?>
					    </li>
					    </ul>
				 </div>
                        
                <?php }else{ ?>
		<ul class="rv_nav">
		      <li><span style="color: #989494;font-size: 13px;"><?=$reviewDataResult['totalReviews'] ;?> Review(s)</span><i></i></li>
		      <li><span style="color: #989494;font-size: 13px;"><?=$reviewDataResult['recommendations'] ;?> Recommendation(s)</span></li>
		</ul>
		<p class="clr"></p>
		      </div>
		      <div class="revwListBxRgt">
		      <div class="rv_midd"> <span class="rv_midd1">
		      <?php if($reviewDataResult['anonymousFlag'] == 'NO'){?> <?=$username?> <?php } else {?> Anonymous <?php }?>
		      </span><span class="rv_midd2">Class of <?=$reviewDataResult['yearOfGraduation'];?></span>
		      <p class="clr"></p>
		      </div>
          
            <?php } ?>
	    
        <?php $maxStringSize = 400;
	    					if($reviewDataResult['placementDescription']){
	    						$review = substr($reviewDataResult['placementDescription'],0,$maxStringSize);
	    					}else if($reviewDataResult['infraDescription']){
	    						$review = substr($reviewDataResult['infraDescription'],0,$maxStringSize);
	    					}else if($reviewDataResult['facultyDescription']){
	    						$review = substr($reviewDataResult['facultyDescription'],0,$maxStringSize);
	    					}else{
	    						$review = substr($reviewDataResult['reviewDescription'],0,$maxStringSize);
	    					}
	    				?>

            			<div class="rv_desc" id="readMore-<?=$courseId?>">
            				<?php echo $review; ?>
            				<?php if($reviewDataResult['placementDescription'] || $review != $reviewDataResult['reviewDescription']){ ?>
            				<a href="javascript:void(0);" onclick="showFullReview(<?php echo $reviewDataResult['reviewId']; ?>);"> ...more</a>
            				<?php } ?>
            			</div>
  
          <div  class="rv_desc" id="readMoreFull<?=$reviewDataResult['reviewId']; ?>" style="display: none;"><?=$reviewDataResult['reviewDescription'];?></div>
	   
		      <div class="rv_hlpful">
				 <?php
					    if($validateuser && ($userSessionData[$sessionId][$reviewDataResult['reviewId']]['userId'] == $userId) && (($userSessionData[$sessionId][$reviewDataResult['reviewId']]['isSetHelpfulFlag'] == 'YES')|| ($userSessionData[$sessionId][$reviewDataResult['reviewId']]['isSetHelpfulFlag'] == 'NO'))){
						       $showHelpfulText = false;
					    } else if($validateuser == 'false' && ($userSessionData[$sessionId][$reviewDataResult['reviewId']]['sessionId'] == $sessionId) && (($userSessionData[$sessionId][$reviewDataResult['reviewId']]['isSetHelpfulFlag'] == 'YES')|| ($userSessionData[$sessionId][$reviewDataResult['reviewId']]['isSetHelpfulFlag'] == 'NO'))){
						       $showHelpfulText = false;
					    } else {
						       $showHelpfulText = true;
					    }
				 ?>
				 <?php if($showHelpfulText){ ?>
					    <p id="rv_helpful_text_<?=$reviewDataResult['reviewId']; ?>" style="color: #c2c0c0; float: left; font-size: 12px; line-height: 22px;">Is this review helpful? <a id="helpfulFlag" name="helpfulFlag" value="Yes" style="color: #006fa2; font-weight:bold " onclick="storeHelpfulFlag(<?=$reviewDataResult['reviewId']; ?>,this,<?=$userId?>); return false;" href="">YES</a><a id="notHelpfulFlag" name="notHelpfulFlag" value="No" style="color: #006fa2; font-weight:bold " onclick="storeHelpfulFlag(<?=$reviewDataResult['reviewId']; ?>,this,<?=$userId?>); return false;" href="">NO</a></p>
					    <p id="rv_thank_text_<?=$reviewDataResult['reviewId']; ?>" style="color: #c2c0c0; float: left; font-size: 12px; line-height: 22px; display:none;">Thank you for your vote</p>
				 <?php } else { ?>
					    <p id="rv_reviewed_text_<?=$reviewDataResult['reviewId']; ?>" style="color: #c2c0c0; float: left; font-size: 12px; line-height: 22px;">Thanks, You have already voted.</p>
				 <?php } ?>
				 
		

	   </div>
		      <p class="clr"></p>
	   </div>

<?php } ?>
                 
<?php if(count($reviewData)>0){ ?>
        <div class="rv_pagination">
           <p>Showing <?php echo count($reviewData['results']); ?> of <?=$reviewData['totalReviews'];?> College Reviews</p>

           <?=$paginationHTML?>

          <div class="clearFix"></div>
          </div>
<?php } ?>


          
          
