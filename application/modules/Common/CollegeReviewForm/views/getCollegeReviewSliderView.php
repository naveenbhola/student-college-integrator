<?php
$reviewDataResult = $reviewData['results'][$courseId];
$URL['permURL'] = $reviewDataResult['review_seo_url'];
$username = $reviewData['reviewerDetails'][$reviewDataResult['reviewId']]['username'];
?>
<ul class="colge-rve-slid" id="" style='width:100%'>
            <li>
            <div class="rv_midd"> <span class="rv_midd1" style="width:90%; word-wrap:break-word;"><?php if($reviewDataResult['anonymousFlag'] == 'NO'){?> <?=$username?> <?php } else {?> Anonymous <?php }?></span><span class="rv_midd2">Class of <?=$reviewDataResult['yearOfGraduation'];?></span>
             <p class="clr"></p>
             <ul class="rv_nav">
             <li><div class="rv_ratng"> Rating <span><?php if(strpos(round($reviewDataResult['reviewAvgRating'],1),'.')){echo round($reviewDataResult['reviewAvgRating'] ,1);}else{echo round($reviewDataResult['reviewAvgRating'],1).'.0';}?><b>/<?php echo $reviewDataResult['ratingParamCount'];?></b></span></div><i></i></li>
             <li style="border-right: 0px;">
		      <?php if($reviewDataResult['recommendCollegeFlag'] == 'YES'){
		      ?>
				 <i class="sprite-bg thumb-up-icon" style="margin-top:1px;vertical-align: middle"></i><span style="color: #878787;font-size: 12px;vertical-align: middle; display: inline-block;">Recommends This Course<span>
            </span></span>
		      <?php
		      }else{
		      ?>
				 <i class="sprite-bg thumb-dwn-icon" style="margin-top:1px;vertical-align: middle"></i><span style="color: #878787;font-size: 12px;vertical-align: middle; display: inline-block;">Doesn't Recommend This Course<span>
            </span></span>
		      <?php
		      }
		      ?>
             </li>
            </ul>
            </div>
	    <?php $maxStringSize = 200;
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
            <div class="rv_hlpful" >
  			<!--div class="rv_help">Is this review helpful? <a href="javascript:void(0);">YES</a></div-->
			<?php
				if($validateuser && ($userSessionData[$sessionId][$reviewDataResult['reviewId']]['userId'] == $userId || $userSessionData[$sessionId][$reviewDataResult['reviewId']]['sessionId'] == $sessionId) && (($userSessionData[$sessionId][$reviewDataResult['reviewId']]['isSetHelpfulFlag'] == 'Yes')|| ($userSessionData[$sessionId][$reviewDataResult['reviewId']]['isSetHelpfulFlag'] == 'No'))){
					    $showHelpfulText = false;
				 } else if($validateuser == 'false' && ($userSessionData[$sessionId][$reviewDataResult['reviewId']]['sessionId'] == $sessionId) && (($userSessionData[$sessionId][$reviewDataResult['reviewId']]['isSetHelpfulFlag'] == 'Yes')|| ($userSessionData[$sessionId][$reviewDataResult['reviewId']]['isSetHelpfulFlag'] == 'No'))){
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

            </li>
</ul>
