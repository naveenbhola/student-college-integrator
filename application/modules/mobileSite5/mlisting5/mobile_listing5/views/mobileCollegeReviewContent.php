<?php 
$ratingText = array(1=>'Poor',2=>'Below Average',3=>'Average',4=>'Above Average',5=>'Excellent');
    $count = 0;
    foreach($courseReviews[$course->getId()]['reviews'] as $courseReviewData) { 
        $URL['permURL'] = $courseReviewData['review_seo_url'];
    ?>
           
           	<dl class="review-detail-layer" style="display:<?php echo ($count > 2) ? 'none' : 'block';?>" id="reviewNo<?=$courseReviewData['id']?>">
                <dt>
                    <h2 class="alumini-title" style="padding:10px;">
                    <span class="user-name" style = "word-wrap: break-word;"><!--<i class="sprite blue-tick"></i>--><a href="#" style="color:#333;"><?php 
                            if($courseReviewData['anonymousFlag'] == 'YES') { 
                                $username = "Anonymous";
                            }
                            else {
                                $username = ucfirst($courseReviewData['username']);
                            }
                        ?>

                        <div class="rv_midd"><span class="rv_midd1" id="username_<?php echo $courseReviewData['id']; ?>" style = "word-wrap: break-word;width:100%;"><?=$username?> </span><span class="user-name" id='year_<?php echo $courseReviewData['id']; ?>'>Class of <?=$courseReviewData['yearOfGraduation'];?></span>


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
                    ?></a> 
                    <div class="clearfix" style="margin:8px 0 0 0;">
                    	<div class="flLt avg-rating-title">Rating: </div>
                    	<div class="ranking-bg"><span id="rating_<?php echo $courseReviewData['id']; ?>"><strong><?php if(strpos($courseReviewData['overallUserRating'],'.')){echo round($courseReviewData['overallUserRating'],1);}else{echo round($courseReviewData['overallUserRating'],1).'.0';}?></strong><sub>/<?php echo $courseReviewData['ratingParamCount'];?></sub></span></div>
                    	<span class="flLt rating-sprtr"> | </span>
                        <div class="recommended-title flLt" style="margin:0;" id="recommend_<?php echo $courseReviewData['id']; ?>">
                           <?php if($courseReviewData['recommendCollegeFlag'] == 'YES') { ?>
                           <i class="sprite thumb-up-icon"></i>
                           <strong style="color:#989494; display: inline-block; padding-top:3px;" class="font-12">Recommended</strong>
                        <?php } else { ?>
                            <i class="sprite thumb-dwn-icon"></i>
                            <strong style="color:#989494; display: inline-block; padding-top:3px;" class="font-12">Not Recommended </strong>
                        <?php } ?>
                       </div>
                    </div>
                    </h2>
                </dt>
                <dd class="alumini-cont">

                    <?php $reviewDataValue = $courseReviewData['reviewRating']; ?>

                	<?php  $this->load->view('mCollegeReviews5/showReviewPageRatingWidgetMobile',array('reviewDataValue' => $reviewDataValue,'ratingTextValue' =>$ratingText));?>
                                                


                                                 <div class="review-detail-content">
                                                               <p>
                    <?php
                        $maxStringSize = 450;
                        if($courseReviewData['placementDescription']){
                                $review = substr($courseReviewData['placementDescription'],0,$maxStringSize);
                            }
                            else if($courseReviewData['infraDescription']){
                                $review = substr($courseReviewData['infraDescription'],0,$maxStringSize);
                            }else if($courseReviewData['facultyDescription']){
                                $review = substr($courseReviewData['facultyDescription'],0,$maxStringSize);
                            }else{
                                $review = substr($courseReviewData['reviewDescription'],0,$maxStringSize);
                            }
                    
                        echo $review; ?>
                        <?php if($courseReviewData['placementDescription'] || $review != $courseReviewData['reviewDescription']){ ?>
                         <a href="#readMoreReviewLayer" data-transition="slide" data-rel="dialog" data-inline="true" onclick=populateReadMoreLayerForCoursePage("<?php echo $courseReviewData['id']; ?>","<?php echo $course->getId(); ?>");>&nbsp;Read More...</a>
                                                                </p>
                        <?php } ?>
								
								<div id="helpful_<?php echo $courseReviewData['id']; ?>" class="rv_hlpfull">
                                <div class="rv_date" style='display: none;' id="posted_<?=$courseReviewData['id']; ?>">Posted: <?php echo date("d M Y",strtotime($courseReviewData['postedDate']));?></div>
								<?php
								    if($validateuser && ($userData['userSessionData'][$userData['sessionId']][$courseReviewData['id']]['userId'] == $userData['userId']) && ($userData['userSessionData'][$userData['sessionId']][$courseReviewData['id']]['isSetHelpfulFlag'] == 'YES' || $userData['userSessionData'][$userData['sessionId']][$courseReviewData['id']]['isSetHelpfulFlag'] == 'NO')){
									$showHelpfulText = false;
								    } else if($validateuser == 'false' && ($userData['userSessionData'][$userData['sessionId']][$courseReviewData['id']]['sessionId'] == $userData['sessionId']) && ($userData['userSessionData'][$userData['sessionId']][$courseReviewData['id']]['isSetHelpfulFlag'] == 'YES' || $userData['userSessionData'][$userData['sessionId']][$courseReviewData['id']]['isSetHelpfulFlag'] == 'NO')){
									$showHelpfulText = false;
								    } else {
									$showHelpfulText = true;
								    }
								?>
								
								<?php if($showHelpfulText){ ?>
								    <p id="rv_helpful_text_<?=$courseReviewData['id']; ?>" style="color: #c2c0c0; float: left; font-size: 12px;">Is this review helpful? <a id="helpfulFlag" name="helpfulFlag" value="Yes" style="color: #006fa2; font-weight:bold " onclick="storeHelpfulFlag(<?=$courseReviewData['id']; ?>,this,<?=$userData['userId'];?>); return false;" href="">YES</a></p>
								    <p id="rv_thank_text_<?=$courseReviewData['id']; ?>" style="color: #c2c0c0; float: left; font-size: 12px; display:none;">Thank you for your vote</p>
								<?php } else { ?>
								    <p id="rv_reviewed_text_<?=$courseReviewData['id']; ?>" style="color: #c2c0c0; float: left; font-size: 12px;">Thanks, You have already voted.</p>
								<?php } ?>

								</div>
					</div>
                </dd>
           </dl>
<?php $count++; 
    } ?>
    <?php if($count > 3) { ?>
           <dl>
                <dd class="alumini-cont"><div class="show-more">
            <a href="javascript:void(0);" onclick="showMoreCollegeReviews(this);">SHOW MORE</a>
                </div></dd>
           </dl>
    <?php } ?>
    <?php if($count > 0) { ?>
    <div id="clg_review_disclaimer" style='font-size: 0.8em; padding-bottom:5px; margin: 5px;'>
        <b style="float:left; display:block;">Disclaimer : </b><span style="display: block; margin-left: 80px; color: rgb(112, 112, 112);">All reviews are submitted by current students & alumni of respective colleges and duly verified by Shiksha.</span>
    </div>
    <?php } ?>
                                <input type="hidden" id="institute_name" name="institute_name" value="<?php echo $institute_name; ?>" />
                                <input type="hidden" id="course" name="course" value="<?php echo $course->getName(); ?>" />
                                <input type="hidden" id="city" name="city" value="<?php echo $course->getCityName(); ?>" />


    <script>
	function storeHelpfulFlag(reviewId, obj, userId) {
	
	    var ajaxURL = "/CollegeReviewForm/CollegeReviewForm/setHelpfulFlag/"+reviewId+"/"+obj.text+"/"+userId;
	    
	    $.ajax({
		url: ajaxURL,
		method:'GET',
		success:function (data) {
            
			$('#rv_thank_text_'+reviewId).show();
			$('#rv_helpful_text_'+reviewId).hide();
            $(".rv_hlpful").find("#rv_helpful_text_"+reviewId).hide();
            $(".rv_hlpful").find("#rv_thank_text_"+reviewId).show();
		},
		error: function(){ alert('Something went wrong...'); }
		
	    });
	}
    </script>
   