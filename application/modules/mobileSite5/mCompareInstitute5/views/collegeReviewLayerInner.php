<?php 
$i=1;
$count = count($collegeReviewData[$singleCourseId]['reviewDetails']);
foreach ($collegeReviewData[$singleCourseId]['reviewDetails'] as $key => $value) {?>
<li <?php if($i == $count){?>class="last-child"><?php } ?>
    <div class="review-list-detail">
        <div class="user-initial"><?php if($value['anonymousFlag'] == 'YES'){?> <span><?php echo "A";?></span><?php } else{ if($collegeReviewData[$singleCourseId]['reviewerDetails'][$value['reviewId']]['avtarimageurl'] != ''){ echo $collegeReviewData[$singleCourseId]['reviewerDetails'][$value['reviewId']]['avtarimageurl'];} else {?><span><?php echo substr($collegeReviewData[$singleCourseId]['reviewerDetails'][$value['reviewId']]['username'], 0, 1);?></span><?php }} ?></div>
        <div class="user-info">
            <p class="user-title"><?php if($value['anonymousFlag'] == 'YES'){echo "Anonymous";} else echo $collegeReviewData[$singleCourseId]['reviewerDetails'][$value['reviewId']]['username'];?></p>
            <?php if($value['yearOfGraduation'] != ''){?>
            <div class="clearfix font-11">
            <p class="flLt">Graduation Year - <?php echo $value['yearOfGraduation'];?></p><?php } ?>
            <p class="flRt"><i class="msprite clock-icon"></i><span id="reviewLayerDatePosting"><?php echo date('dS M Y',strtotime($value['postedDate']));?></span></p>
            </div>
            <div class="review-rating-div">
                <div class="flLt"><?php if($value['reviewAvgRating'] != '' && $value['reviewAvgRating'] != 0) {?>Rated <span class="review-rating"><?=$value['reviewAvgRating'];?>/5</span> <?php } if(!($value['recommendCollegeFlag'] == '' || $value['reviewAvgRating'] == '' || $value['reviewAvgRating'] == 0)){ ?><span class="review-sprtr"> | </span><?php } if($value['recommendCollegeFlag'] == 'YES'){?> <i class="msprite reco-icon"></i> Recommended<?php } if($value['recommendCollegeFlag'] == 'NO'){?> <i class="msprite non-reco-icon"></i> Not Recommended <?php } ?></div>
            </div>
        </div>
    </div>
    <div class="review-list-content">
        <p><span id="rlrd<?echo $start.$key;?>"><?php echo strlen($value['reviewDescription']) > 1000 ? substr($value['reviewDescription'], 0, 997).'...' : $value['reviewDescription']?></span><?php if(strlen($value['reviewDescription']) > 1000) {?><span id = "rlrdf<?echo $start.$key;?>" style="display:none"><?=$value['reviewDescription'];?></span><a href="javascript:void(0);" class="view-more-link" onclick="this.style.display='none'; $('#rlrd<?echo $start.$key;?>').hide(); $('#rlrdf<?echo $start.$key;?>').show();">View More</a><?php }?></p>
    </div>
</li>
<?php $i++;} ?>