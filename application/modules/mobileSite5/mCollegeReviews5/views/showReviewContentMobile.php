
<div class="colgRvwSlidrBx bordRdus3px" style="margin:0; padding-bottom:0; border:0 none; background:#fff;">
	<div class="colgRvwNew-p1"> 
        <h1>
            <div class="rv_title">
            	<a href="<?php echo $reviewData['instituteUrl'].'#course-reviews-sec';?>"><?php echo $reviewData['instituteName']; ?></a>
            	<?php if(!empty($reviewData['cityName'])) { echo ', <p class="rv_add">'.$reviewData['cityName'].'</p>'; } ?>
            </div> 
            <p class="rv_course">
            	<a style="font-weight:normal;" href="<?php echo $reviewData['courseUrl'].'#course-reviews';?>"><?php echo $reviewData['courseName']; ?></a>
            </p>
        </h1> 
    </div>
</div>


<div class="colgRvwHP revewNav whtBx2" style='padding: 0'>
    <div class="revwListBx revwListBx2 whtBx bordRdus3px" style="border:0 none; box-shadow:none; padding-top:0;">
        <p class="clr"></p>
        <div class="rv_midd" style="border:none;">
        	<span class="rv_midd1" style = "word-wrap: break-word;width:100%; display:inline;">
        		<?php
                    if($reviewData['anonymousFlag'] == 'NO') { 
                        echo $reviewUserDetails['firstname'].' '.$reviewUserDetails['lastname']; 
                    } else {
                        echo 'Anonymous';
                    } 
                ?>
        	</span>
            <span class="rv_midd2">
            	Class of <?php echo $reviewData['yearOfGraduation'];?>
            </span>
            <p class="clr"></p>
      		<ul class="rv_nav" style="border:none;">
        		<li>
      				<div class="rv_ratng">Rating:
      					<span>
      						<?php 
		                        if(strpos(round($reviewData['reviewAvgRating'],1),'.')) {
		                            echo round($reviewData['reviewAvgRating'] ,1);?><b>/<?php echo $reviewData['ratingParamCount'];?></b>
		                        <?php } else {
		                            echo round($reviewData['reviewAvgRating'],1).'.0'?><b>/<?php echo $reviewData['ratingParamCount'];?> </b>
		                        <?php }	?>
      					</span>
      				</div>
        			<i></i>
        		</li>

        		<li style="border-right: 0px;">
        			<?php if($reviewData['recommendCollegeFlag'] == 'YES') { ?>
                            <i class="sprite thumb-up-icon" style="margin-top:1px;vertical-align: middle"></i>
                            <span style="color: #878787;font-size: 11px;vertical-align: middle; display: inline-block;">
                            	Recommended
          					</span>
                    <?php } else { ?>
                    		<i class="sprite thumb-dwn-icon" style="margin-top:1px;vertical-align: middle"></i>
                            <span style="color: #878787;font-size: 11px;vertical-align: middle; display: inline-block;">
                            	Not Recommended
          					</span>
                    <?php } ?>     
                </li>
        	</ul>
        </div>
            
        <div class="rv_desc" id="readMoreFull147" style="bordertop:none;">
        	<p><?=$reviewData['reviewDescription'];?></p>
        </div>

         <?php $imageArray = array('Value for Money' => 'permalink-sprite review-worth-icon',
                                        'Crowd & Campus Life' =>'permalink-sprite review-crowd-icon',
                                        'Salary & Placements' => 'permalink-sprite review-placmnt-icon',
                                        'Campus Facilities' => 'permalink-sprite review-campus-icon',
                                        'Faculty' => 'permalink-sprite review-faculty-icon',
                                        'Industry Exposure and Internships' => 'permalink-sprite industrial-exp-icn'
                                     
                    );?>   


             <?php $reviewDataValue = $reviewData['ratingValue'];?>       

            <?php $this->load->view('mCollegeReviews5/courseReviewsContentRatingWidgetMobile',array('imageToView' => $imageArray,'reviewDataValue' => $reviewDataValue));?>

       

        <div style="margin:20px 0 10px;">
        	<?php
                if($validateuser && ($userSessionData[$sessionId][$reviewData['reviewId']]['userId'] == $userId) && ($userSessionData[$sessionId][$reviewData['reviewId']]['isSetHelpfulFlag'] == 'YES' || $userSessionData[$sessionId][$reviewData['reviewId']]['isSetHelpfulFlag'] == 'NO')) {
					$showHelpfulText = false;
                } else if($validateuser == 'false' && ($userSessionData[$sessionId][$reviewData['reviewId']]['sessionId'] == $sessionId) && ($userSessionData[$sessionId][$reviewData['reviewId']]['isSetHelpfulFlag'] == 'YES' || $userSessionData[$sessionId][$reviewData['reviewId']]['isSetHelpfulFlag'] == 'NO')) {
                    $showHelpfulText = false;
                } else {
                    $showHelpfulText = true;
                }
            ?>
            <?php if($showHelpfulText){ ?>
                <p id="rv_helpful_text_<?=$reviewData['reviewId']; ?>" style="color: #c2c0c0; float: left; font-size: 12px; line-height: 22px;">
                    Is this review helpful? 
                    <a id="helpfulFlag" name="helpfulFlag" value="YES" style="color: #006fa2; font-weight:bold" onclick="storeHelpfulFlag('<?=$reviewData['reviewId']; ?>',this,'<?=$userId; ?>'); return false;" href="javascript:void(0)">YES</a>
                    <a id="notHelpfulFlag" name="notHelpfulFlag" value="No" style="color: #006fa2; font-weight:bold " onclick="storeHelpfulFlag(<?=$reviewData['reviewId']; ?>,this,<?=$userId?>); return false;" href="">NO</a>
                </p>
                <p id="rv_thank_text_<?=$reviewData['reviewId']; ?>" style="color: #c2c0c0; float: left; font-size: 12px; line-height: 22px; display:none;">
                    Thank you for your vote
                </p>
            <?php } else { ?>
                <p id="rv_reviewed_text_<?=$reviewData['reviewId']; ?>" style="color: #c2c0c0; float: left; font-size: 12px; line-height: 22px;">
                    Thanks, You have already voted.
                </p>
            <?php } ?>
            
        </div>
    </div>
</div>
           
