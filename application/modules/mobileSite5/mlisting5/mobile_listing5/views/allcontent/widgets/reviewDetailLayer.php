<?php
$userName = ($reviewRow['anonymousFlag']=='YES')?'Anonymous':ucwords(strtolower($reviewRow['reviewerDetails']['username']));
            if($reviewRow['yearOfGraduation'])
                $batch = " Batch of ".$reviewRow['yearOfGraduation'];

            $reviewTitle = trim($reviewRow['reviewTitle']);

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

?>

<h1 class="main-titl"><?=$userName.' | '.$batch.' review';?><a class="" onclick = "closeReviewDetailLayer('<?php echo $reviewRow['id'];?>');">&times;</a></h1>

<div class="lcard art-crd">
      <div class="revw-card">
            <p class="rating-p">
                <span class="rat-label">Rating:</span>
               <span class="rat-col" ga-attr="VIEW_REVIEW_RATING" onclick ="showRatingLayer('rating-detail_','<?php echo $reviewRow['id'];?>');" id="rating-detail_layer<?php echo $reviewRow['id'];?>"><?php echo number_format($reviewRow['averageRating'], 1, '.', '');?>/<span class="total">5</span><i class="open-tick"></i></span>
                        <span class="rat-sep"> | </span>
                        
                            <?php if($reviewRow['recommendCollegeFlag'] == 'YES'){?>
                                <span class="rco-tag"><i class="green-ico"></i>Recommended</span>
                            <?php }else{?>
                                <span class="rco-tag"><i class="red-ico"></i>Not Recommended</span>
                            <?php } ?>

            </p>
            <div class="rating-col-n rvw-fix" style="display:none" id="rating-detail_<?php echo $reviewRow['id'];?>">
                            <ol>
                                 <?php foreach($reviewRating[$reviewRow['id']] as $desc=>$rating){?>
                                    <li>
                                        <label><?php echo $desc;?></label>
                                        <p class="star-r">
                                            <span class="star-rating">
                                                <?php for($i = 0;$i<$rating;$i++){?>
                                                    <span class="rating-primary full"></span>
                                                <?php } for($i = 0;$i<5-$rating;$i++){?>
                                                    <span class="rating-primary none"></span>
                                                <?php } ?>
                                            </span>
                                        </p>
                                    </li>
                                <?php } ?>
                            </ol>
                        </div>
                    </div>
                    <?php
                        if($reviewRow['courseName']){
                    ?>
                            <p class="rvw-titl"><?php echo 'Course reviewed : '.htmlentities($reviewRow['courseName']);?></p>
                    <?php
                        }
                        if($reviewTitle){
                    ?>
            
            <p class="titl-rvw"><?php echo nl2br(htmlentities($reviewTitle));?></p>

            <?php } 
                        foreach ($reviewSegments as $reviewSegmentName => $reviewSegmentText) {
                                ?>

                                    <p class="revw-info">
                                        <?php if($reviewSegmentName != 'Description'){ ?>
                                            <strong><?php echo $reviewSegmentName;?>: </strong>
                                        <?php 
                                             } 
                                            echo nl2br(htmlentities($reviewSegmentText));
                                        ?>
                                    </p>

                                            
                        <?php }
                        ?>
            <div class="rating-col-n">
                            <ol>
                               <?php foreach($reviewRating[$reviewRow['id']] as $desc=>$rating){?>
                                    <li>
                                        <label><?php echo $desc;?></label>
                                        <p class="star-r">
                                            <span class="star-rating">
                                                <?php for($i = 0;$i<$rating;$i++){?>
                                                    <span class="rating-primary full"></span>
                                                <?php } for($i = 0;$i<5-$rating;$i++){?>
                                                    <span class="rating-primary none"></span>
                                                <?php } ?>
                                            </span>
                                        </p>
                                    </li>
                                <?php } ?>
                            </ol>
                        </div> 

                        <div class="hlp-t rv_hlpfulYES">
                            <?php
                                if($validateuser && ($userSessionData[$sessionId][$reviewRow['id']]['userId'] == $userId) && (($userSessionData[$sessionId][$reviewRow['id']]['isSetHelpfulFlag'] == 'YES') || ($userSessionData[$sessionId][$reviewRow['id']]['isSetHelpfulFlag'] == 'YES'))) {
                                    $showHelpfulText = false;
                                } else if($validateuser == 'false' && ($userSessionData[$sessionId][$reviewRow['id']]['sessionId'] == $sessionId) && (($userSessionData[$sessionId][$reviewRow['id']]['isSetHelpfulFlag'] == 'YES') || ($userSessionData[$sessionId][$reviewRow['id']]['isSetHelpfulFlag'] == 'NO'))) {
                                    $showHelpfulText = false;
                                } else {
                                    $showHelpfulText = true;
                                }
                              
                            ?>
                        <?php if(!(isset($validateuser[0]['userid']) && $validateuser[0]['userid'] == $reviewRow['clientId'])) {?>
                            <div class="helpful-txt">
                                <?php if($showHelpfulText){ ?>
                                 <div id = "rv_helpful_text_<?=$reviewRow['id']; ?>" >
                                    <span class="hlp-spn">Is this review helpful
                                        <a id="helpfulFlag" name="helpfulFlag" value="YES"  onclick="storeHelpfulFlag(<?=$reviewRow['id']; ?>,this,<?=$userId?>); return false;" href="javascript:void(0);" ga-attr="YES_REVIEW_HELPFUL">YES</a>
                                        <b>|</b>
                                        <a id="notHelpfulFlag" name="notHelpfulFlag" value="NO" onclick="storeHelpfulFlag(<?=$reviewRow['id']; ?>,this,<?=$userId?>); return false;" href="javascript:void(0);" ga-attr="NO_REVIEW_HELPFUL">NO</a>
                                    </span>
                                </div>
                                <span class="hlp-spn span-helptxt" id="rv_thank_text_<?=$reviewRow['id']; ?>" style="color: #666; font-size: 12px; line-height: 0px; display:none;">
                                        Thank you for your vote
                                    </span>
                                
                                <?php } else { ?>
                                    <span class="hlp-spn span-helptxt" id="rv_thank_text_<?=$reviewRow['id']; ?>" >
                                        Thanks, You have already voted.
                                    </span>
                                <?php } ?>
                            </div>
                            <?php } ?>
                        <p class="hlp-spn r-t">Posted <?php echo date('d M Y',strtotime($reviewRow['postedDate']));?></p>
                     </div>          
                      <?php if(!empty($reviewReplies[$reviewRow['id']])) {?>        
                        <div class="new-rply-content">
                            <div class="rply-txt rvw-titl">
                                <strong><?=$reviewRow['institeteName'];?> replied :</strong>
                                <p><?=nl2br_Shiksha(sanitizeAnAMessageText($reviewReplies[$reviewRow['id']],'reply'));?></p>
                            </div>
                        </div>
                    <?php } ?>
            
</div>
<input type="hidden" id="prevReview" name="prevReview" value='<?php echo $prevReview;?>'>
<input type="hidden" id="nextReview" name="nextReview" value='<?php echo $nextReview;?>'>
<input type="hidden" id="reviewIndex" name="reviewIndex" value='<?php echo $index+1;?>'>





