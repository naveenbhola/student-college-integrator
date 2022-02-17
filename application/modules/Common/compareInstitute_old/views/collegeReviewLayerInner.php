<?php 
$i=1;
$count = count($collegeReviewData[$singleCourseId]['reviewDetails']);
foreach ($collegeReviewData[$singleCourseId]['reviewDetails'] as $key => $value) {?>
<li <?php if($i == $count){?>class="last-child"><?php } ?>
   <div class="review-list-detail">
        <div class="user-initial"><?php if($value['anonymousFlag'] == 'YES'){echo "<span> A </span>";} else{ if($collegeReviewData[$singleCourseId]['reviewerDetails'][$value['reviewId']]['avtarimageurl'] != ''){ echo $collegeReviewData[$singleCourseId]['reviewerDetails'][$value['reviewId']]['avtarimageurl'];} else {?><span><?php echo substr($collegeReviewData[$singleCourseId]['reviewerDetails'][$value['reviewId']]['username'], 0, 1);?></span><?php } }?></div>
            <div class="user-info">
                <p class="user-title"><?php if($value['anonymousFlag'] == 'YES'){echo "Anonymous";} else echo $collegeReviewData[$singleCourseId]['reviewerDetails'][$value['reviewId']]['username'];?></p>
                <?php if($value['yearOfGraduation'] != '') {?>
                <p>Graduation Year - <?php echo $value['yearOfGraduation'];?><span class="review-sprtr"> | </span><?php } if($value['reviewAvgRating'] != 0){?><span class="review-rate">Rated</span> <span class="review-rating"><?php echo $value['reviewAvgRating'];?>/5</span> <span class="review-sprtr"> | </span> <?php } if($value['recommendCollegeFlag'] == 'YES'){?>Recommended<i class="cmpre-sprite reco-icon"></i><?php } if($value['recommendCollegeFlag'] == 'NO'){?> <i class="cmpre-sprite non-reco-icon"></i> Not Recommended <?php } ?></p>
                <p class=""><i class="cmpre-sprite ic-clock"></i><?php echo date('dS M Y',strtotime($value['postedDate']));?></p>
            </div>
    </div>
    <div class="review-list-content">
        <p><span id="rlrd<?echo $start.$key;?>"><?php echo strlen($value['reviewDescription']) > 350 ? substr($value['reviewDescription'], 0, 347).'...' : $value['reviewDescription']?><?php if(strlen($value['reviewDescription']) > 350) {?>
        <a href="javascript:void(0);" class="view-more-cutoffs" onclick="this.style.display='none'; $j('#rlrd<?echo $start.$key;?>').hide(); $j('#rlrdf<?echo $start.$key;?>').show();">view more</a><?php }?></span><?php if(strlen($value['reviewDescription']) > 350) {?><span id = "rlrdf<?echo $start.$key;?>" style="display:none"><?=$value['reviewDescription'];?></span><?php } ?></p>
    </div>
 <div class="clr"></div>
</li>  
<?php $i++;} ?>