<div class="other-details-wrap clear-width" style="margin-bottom:0;">

    <div class="clearFix"></div>             
    <div class="review-detail-layer permalink-review-layer" style="margin-top:0;">
        <div class="course-inst-detail-sec">
            <!-- <span><a href="< ?php echo $categoryPageURL;?>">< ?php echo $pageFor;?> in India</a></span>
            <span class="rvw-arrw"> >> </span>
            <span class="mb14"><a href="< ?php echo $reviewURL;?>">College Reviews</a></span> -->
            <h1 style="font-weight:normal;">
                <p class="permalink-inst-title" style='margin-bottom:8px;'>
                    <a style="text-decoration:none;font-size:25" href="<?php echo $reviewData['instituteUrl'].'/reviews';?>"><?php echo $reviewData['instituteName']; ?></a><?php if(!empty($reviewData['cityName'])) { echo ', <span class="per-city-name">'.$reviewData['cityName'].'</span>'; } ?>
                </p>
                <p style="font-size:14px;">
                    <a style="text-decoration:none;color:#006FA2" href="<?php echo $reviewData['instituteUrl'].'/reviews?course='.$reviewData['courseId'];?>"><?php echo $reviewData['courseName']; ?></a>
                </p>
            </h1>
        </div>    

        <div class="permalink-share-list clear-width">

            <?php $socialSharingParams['view'] = 'permalinkTop'; $this->load->view('common/socialSharing', $socialSharingParams); ?>

        </div>   


        <div class="flLt">

            <div class="review-header clear-width">
                <div class="flLt">
                    <!-- <i class="sprite-bg review-mark-icon"></i> -->
                    <strong>
                        <?php
                        if($reviewData['anonymousFlag'] == 'NO') { 
                            echo $reviewUserDetails['firstname'].' '.$reviewUserDetails['lastname']; 
                        } else {
                            echo 'Anonymous';
                        } ?>
                    </strong>
                </div>
                <div class="flLt" style="line-height:20px;">
                    <em>Class of <?php echo $reviewData['yearOfGraduation'];?></em>
                    <span style="margin:0"> <b>|</b> </span>
                    <em>Rating</em>
                    <div class="ranking-bg" style="float:none; display:inline-block; line-height:15px;">                        
                        <?php 
                        if(strpos(round($reviewData['reviewAvgRating'],1),'.')) {
                            echo round($reviewData['reviewAvgRating'] ,1)?><sub>/<?php echo $reviewData['ratingParamCount'];?></sub>
                        <?php } else {
                            echo round($reviewData['reviewAvgRating'],1).'.0';?><sub>/<?php echo $reviewData['ratingParamCount'];?></sub>
                        <?php }
                        ?>                    
                    </div>
                    <span> <b>|</b> </span>
                    <?php
                          if($reviewData['recommendCollegeFlag'] == 'YES') { ?>
                            <i class="sprite-bg thumb-up-icon"></i>
                            <strong style="color:#989494;padding:0; font-size:12px !important;" class="font-12">Recommends This Course</strong>
                    <?php } else { ?>
                             <i class="sprite-bg thumb-dwn-icon"></i>
                            <strong style="color:#989494;padding:0; font-size:12px !important;" class="font-12">Doesn't Recommend This Course</strong>
                    <?php } ?>                                            
                </div>
            </div>
            <div class="review-detail-sec">
                <div class="flLt review-detail-left">
                    <p><?=$reviewData['reviewDescription'];?></p>
                </div>

             <?php $imageArray = array('Value for Money' => 'permalink-sprite review-worth-icon',
                                        'Crowd & Campus Life' =>'permalink-sprite review-crowd-icon',
                                        'Salary & Placements' => 'permalink-sprite review-placmnt-icon',
                                        'Campus Facilities' => 'permalink-sprite review-campus-icon',
                                        'Faculty' => 'permalink-sprite review-faculty-icon',
                                        'Industry Exposure and Internships' => 'permalink-sprite industrial-exp-icn'
                                     
                    );?>   

            <?php $this->load->view('CollegeReviewForm/showReviewPageRatingWidget',array('imageToView' => $imageArray));?>

            </div>

            <div class="rv_hlpful">

                <?php
                    if($validateuser && ($userSessionData[$sessionId][$reviewData['reviewId']]['userId'] == $userId) && (($userSessionData[$sessionId][$reviewData['reviewId']]['isSetHelpfulFlag'] == 'YES') || ($userSessionData[$sessionId][$reviewData['reviewId']]['isSetHelpfulFlag'] == 'NO'))) {
                        $showHelpfulText = false;
                    } else if($validateuser == 'false' && ($userSessionData[$sessionId][$reviewData['reviewId']]['sessionId'] == $sessionId) && (($userSessionData[$sessionId][$reviewData['reviewId']]['isSetHelpfulFlag'] == 'YES') || ($userSessionData[$sessionId][$reviewData['reviewId']]['isSetHelpfulFlag'] == 'NO'))) {
                        $showHelpfulText = false;
                    } else {
                        $showHelpfulText = true;
                    }
                ?>
                <?php if($showHelpfulText){ ?>
                    <p id="rv_helpful_text_<?=$reviewData['reviewId']; ?>" style="color: #c2c0c0; float: right; font-size: 12px; line-height: 22px;">
                        Is this review helpful? 
                        <a id="helpfulFlag" name="helpfulFlag" value="YES" style="color: #006fa2; font-weight:bold" onclick="storeHelpfulFlag('<?=$reviewData['reviewId']; ?>',this,'<?=$userId?>'); return false;" href="javascript:void(0);">YES</a>
                        <b>|</b>
                        <a id="notHelpfulFlag" name="notHelpfulFlag" value="NO" style="color: #006fa2; font-weight:bold" onclick="storeHelpfulFlag('<?=$reviewData['reviewId']; ?>',this,'<?=$userId?>'); return false;" href="javascript:void(0);">NO</a>
                    </p>
                    <p id="rv_thank_text_<?=$reviewData['reviewId']; ?>" style="color: #c2c0c0; float: right; font-size: 12px; line-height: 22px; display:none;">
                        Thank you for your vote
                    </p>
                <?php } else { ?>
                    <p id="rv_reviewed_text_<?=$reviewData['reviewId']; ?>" style="color: #c2c0c0; float: right; font-size: 12px; line-height: 22px;">
                        Thanks, You have already voted.
                    </p>
                <?php } ?>
                
            </div>
        </div>

        <div class="permalink-share-list-2 clear-width" style="border-bottom:none !important;margin-bottom:0px">

            <?php $socialSharingParams['view'] = 'permalinkBottom'; $this->load->view('common/socialSharing', $socialSharingParams); ?>
            
        </div> 
       
        <div class="clearFix"></div>
    </div>
    
    <?php if($courseReviewsCount >= 3) { ?>
    <div class="shw-more-rvw">
        <a href="<?php echo $reviewData['courseUrl'].'#course-reviews';?>" >View Other Reviews</a>
    </div>
    <?php } ?>
   
</div>                 
    
