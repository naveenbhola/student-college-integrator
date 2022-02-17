<?php 
    $count = 0;
    foreach($courseReviews[$course->getId()]['reviews'] as $courseReviewData) { 
    	$style = "";
    	if($count == 0) {
    		$style = "margin-top:75px;";
    	}
        $URL['permURL'] = $courseReviewData['review_seo_url'];
    	?>
    <div class="review-detail-layer" style="<?php echo $style;?>display:<?php echo ($count > 2) ? 'none' : 'block';?>" id="reviewNo<?=$courseReviewData['id']?>">
        <div class="flLt" style="width:100%;">
            <div class="review-header clear-width">
                <div class="flLt" style="position: relative;">
                    <i class="sprite-bg review-mark-icon"></i>
                    <strong onmouseenter="$j(this).parent().find('.avgRating-tooltip').show('fast');" onmouseleave="$j(this).parent().find('.avgRating-tooltip').hide('fast');">
                        <?php 
                            if($courseReviewData['anonymousFlag'] == 'YES') { 
                                echo "Anonymous";
                            }
                            else {
                                if(strlen($courseReviewData['username'])>18){
                                    $courseReviewData['username'] = substr($courseReviewData['username'], 0,18)." ...";
                                }
                                echo ucfirst($courseReviewData['username']);
                            }
                        ?>
                    </strong>
                    <?php 
                        if($courseReviewData['anonymousFlag'] == 'YES') {  ?>
                            <div class="avgRating-tooltip" style="display:none;width:260px;top:32px; padding:9px;">
                                <i class="common-sprite avg-tip-pointer" style="left:26px;"></i>
                                    <p>The student has been verified by Shiksha. However, the student has chosen to stay anonymous.</p>
                            </div>

                        <?php }
                        else { ?>
                            <div class="avgRating-tooltip" style="display:none;width:360px;top:32px; padding:9px;">
                                <i class="common-sprite avg-tip-pointer" style="left:26px;"></i>
                                    <p>This student has been verified by Shiksha.</p>
                            </div>
                        <?php }
                    ?>
                </div>
                <div class="flLt" style="line-height:23px;">
                    <em>Class of <?php echo $courseReviewData['yearOfGraduation'];?></em>
                    <span style="margin:0"> | </span>
                    <em>Rating</em>
                    <div class="ranking-bg" style="float:none; display:inline-block; line-height:15px;"><?php echo round($courseReviewData['overallUserRating'],1);?><sub>/<?php echo $courseReviewData['ratingParamCount'];?></sub></div>
                    <span> | </span>
                    <?php if($courseReviewData['recommendCollegeFlag'] == 'YES') { ?>
                        <i class="sprite-bg thumb-up-icon"></i>
                        <strong style="color:#989494" class="font-12">Recommends This Course</strong>
                    <?php }
                    else { ?>
                        <i class="sprite-bg thumb-dwn-icon"></i>
                        <strong style="color:#989494" class="font-12">Doesn't Recommend This Course</strong>
                    <?php } ?>
                </div>
                    </div>
            <div class="review-detail-sec">
                    <div class="flLt review-detail-left">
                        <div style="word-wrap:break-word">
                        <?php 
                            $maxStringSize = 400;
                            if($courseReviewData['placementDescription']){
                                $review = substr($courseReviewData['placementDescription'],0,$maxStringSize);
                            }else if($courseReviewData['infraDescription']){
                                $review = substr($courseReviewData['infraDescription'],0,$maxStringSize);
                            }else if($courseReviewData['facultyDescription']){
                                $review = substr($courseReviewData['facultyDescription'],0,$maxStringSize);
                            }else{
                                $review = substr($courseReviewData['reviewDescription'],0,$maxStringSize);
                            }
                        echo $review; 
                        ?>
                        <?php if($courseReviewData['placementDescription'] || $review != $courseReviewData['reviewDescription']){ ?>
                            <a href="javascript:void(0);" onclick="showFullReview(<?php echo $courseReviewData['id']; ?>);">&nbsp;Read More...</a>
                        <?php } ?>
			    
			    <?php
				if($validateuser && ($userData['userSessionData'][$userData['sessionId']][$courseReviewData['id']]['userId'] == $userData['userId']) && (($userData['userSessionData'][$userData['sessionId']][$courseReviewData['id']]['isSetHelpfulFlag'] == 'YES')|| ($userData['userSessionData'][$userData['sessionId']][$courseReviewData['id']]['isSetHelpfulFlag'] == 'NO'))){
				    $showHelpfulText = false;
				} else if($validateuser == 'false' && ($userData['userSessionData'][$userData['sessionId']][$courseReviewData['id']]['sessionId'] == $userData['sessionId']) && (($userData['userSessionData'][$userData['sessionId']][$courseReviewData['id']]['isSetHelpfulFlag'] == 'YES')|| ($userData['userSessionData'][$userData['sessionId']][$courseReviewData['id']]['isSetHelpfulFlag'] == 'NO'))){
				    $showHelpfulText = false;
			        } else {
				    $showHelpfulText = true;
				}
			    ?>
			    
			    <div>
			    <?php if($showHelpfulText){ ?>
				<p id="rv_helpful_text_<?=$courseReviewData['id']; ?>" style="color: #c2c0c0; float: left; font-size: 12px; margin-top: 6px;">Is this review helpful? <a id="helpfulFlag" name="helpfulFlag" value="Yes" style="color: #006fa2; font-weight:bold " onclick="storeHelpfulFlag(<?=$courseReviewData['id']; ?>,this,<?=$userData['userId'];?>); return false;" href="">YES</a><a id="notHelpfulFlag" name="notHelpfulFlag" value="No" style="color: #006fa2; font-weight:bold " onclick="storeHelpfulFlag(<?=$courseReviewData['id']; ?>,this,<?=$userData['userId'];?>); return false;" href="">NO</a></p>
				<p id="rv_thank_text_<?=$courseReviewData['id']; ?>" style="color: #c2c0c0; float: left; font-size: 12px; margin-top: 6px; display:none;">Thank you for your vote</p>
			    <?php } else { ?>
				<p id="rv_reviewed_text_<?=$courseReviewData['id']; ?>" style="color: #c2c0c0; float: left; font-size: 12px; margin-top: 6px;">Thanks, You have already voted.</p>
			    <?php } ?>
			    </div>


			</div>
			
                        <?php

                        $isInstituteReplyExist = false;//from new table

                        if(isset($validateuser[0]['userid']) && $validateuser[0]['userid'] == $course->getClientId() && $courseReviewData['instituteReply']=='')
                        {
                        ?>

                          <a id="rplyLink_<?=$courseReviewData['id']?>" class="rv-lnkk" onclick ="showIntituteReplyBox('<?=$courseReviewData['id']?>')" style="display: block; margin-top: 33px;" href="javascript:void(0)">Reply</a>
                        <div class="rv-reply-all findAllCmtBox" id="replyBox-<?=$courseReviewData['id']?>" style="display: none;">
                              <div class="txtareaBx">
                          <textarea class="txtara" id="replyTxt-<?=$courseReviewData['id']?>" validate="validateStr" minlength=1 maxlength=1000 placeholder="Add Comment"></textarea>
                                </div>
                            <input type="button" value="Submit" onclick="saveInstituteReply('<?=$courseReviewData['id']?>','<?=$courseReviewData['courseId']?>','<?=$validateuser[0]['userid']?>','<?=$institute_url;?>','<?=addslashes($institute_name);?>')" class="orange-button" style="margin-top:10px;" >
                        </div>

                          
                          <!---  <div class="review-response-sec"><a onclick ="showReplyBox()" href="#">Reply</a></div>-->
                        <?php
                        }
                        else
                        {
                            if($courseReviewData['instituteReply'] != '')
                            {
                        ?>
                            <div style="margin:30px 0 0 20px; float:left" >
                                <p style="border-bottom: 1px solid #E1E1E1; padding-bottom: 5px;"><i class="institute-cin"></i></i><a style="line-height: 20px;" href="<?=$institute_url;?>" ><?php echo $institute_name;?>'s</a>   <strong style="font-weight: normal;
color: #1D1D1D;">response</strong></p><?=$courseReviewData['instituteReply']?></div>
                        <?php
                            }
                        }
                        ?>
                    </div>

                <?php $this->load->view('listing/national/widgets/courseReviewsContentRatingWidget',array('rating' => $courseReviewData['reviewRating']));?>
                <div class="clearFix"></div>
            </div>
        </div>
            <div class="clearFix"></div>
    </div>
<?php $count++; 
} ?>
<?php if($count > 3) { ?>
<div class="show-more-sec">
    <a href="javascript:void(0);" onclick="showMoreCollegeReviews(this);">Show More</a>
</div>         
<?php } ?>
<?php if($count > 0) { ?>
    <div id="clg_review_disclaimer" style="margin: 10px 0px;">
        <b style="width:87px; float:left; display:block">Disclaimer:</b> 
        <span style="display: block; margin-left: 80px; color: rgb(112, 112, 112);">All reviews are submitted by current students & alumni of respective colleges and duly verified by Shiksha.</span>
    </div>
<?php } ?>

<script>
    function storeHelpfulFlag(reviewId, obj, userId) {
	
        var url = "/CollegeReviewForm/CollegeReviewForm/setHelpfulFlag/"+reviewId+"/"+obj.text+"/"+userId;
        new Ajax.Request(url,
        {
                method:'get',
                onSuccess:function () {
                        $j('#rv_thank_text_'+reviewId).show();
                        $j('#rv_helpful_text_'+reviewId).hide();
                },
                onFailure: function(){ alert('Something went wrong...'); }
        });	
    }
</script>