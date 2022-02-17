<?php 
$ratingText = array(1=>'Poor',2=>'Below Average',3=>'Average',4=>'Above Average',5=>'Excellent');

function printRating($rating,$starFilledClass = 'rating-star-filled', $starEmptyClass = 'rating-star') {
    $rating = round($rating);
    $remainingStars = 5 - $rating;
    $html = '';
    for($i = 1;$i<=$rating;$i++) {
        $html .= '<span class="shortlist-sprite '.$starFilledClass.'"></span>';
    }
    for($i = 1;$i<=$remainingStars;$i++) {
        $html .= '<span class="shortlist-sprite '.$starEmptyClass.'"></span>';
    }
    return $html;
}
    foreach($courseReviews as $courseId=>$courseReviewData) {
?>
    	<div class="accordian-content clearfix" style="padding:0;">
			<dl>
                <dt class="ques-title">
                    <span class="user-name"><strong><?php 
                            if($courseReviewData['anonymousFlag'] == 'YES') { 
                                echo "Anonymous";
                            }
                            else {
                                echo ucfirst($courseReviewData['username']);
                            }?></strong> &nbsp; Class of <?php echo $courseReviewData['yearOfGraduation'];?></span>
                    <div class="clearfix" style="margin:8px 0 0 0;">
                        <div class="flLt avg-rating-title">Rating: </div>
                        <div class="ranking-bg"><strong><?php echo $courseReviewData['overallUserRating']?></strong><sub>/<?php echo $courseReviewData['ratingParamCount'];?></sub></div>
                        <span class="flLt rating-sprtr" style="margin-top:0;"> | </span>
                        <div class="recommended-title flLt" style="margin:0; line-height:18px;">
                        <?php if($courseReviewData['recommendCollegeFlag'] == 'YES') { ?>
                           <i class="sprite thumb-up-icon"></i>
                           <strong>Recommended</strong>
                        <?php
                        }else { ?>
                           <i class="sprite thumb-dwn-icon"></i>
                            <strong>Not Recommended </strong>
                        <?php
                        }?>
                       </div>
                    </div>
                </dt>
				<dd class="alumini-cont">
        			 
                     <?php $reviewDataValue = $courseReviewData['reviewRating']; ?>


                      <?php  $this->load->view('mCollegeReviews5/showReviewPageRatingWidgetMobile',array('reviewDataValue' => $reviewDataValue,'ratingTextValue' =>$ratingText));?>
                        <div class="review-detail-content college-review-detail-content" style="padding-bottom:0px;">
                            <p class="review-desc">
                                <?php
                                    $maxStringSize = 250;
                                    echo substr($courseReviewData['reviewDescription'],0,$maxStringSize);
                                ?><a href="javascript:void(0);" onclick="showMoreDesc(this);"> Read More...</a><span class="remaining-text" style="display:none;"><?php
                                    echo substr($courseReviewData['reviewDescription'],$maxStringSize);
                                ?></span>
                            </p>
                            
                        </div>
                </dd>
			</dl>
	    </div>
<?php
    }
if(($reviewOffset+3) < $totalReviewsCount)
{
?>
<div class="load-more-block">
        <a href="#" class="btn-load-more" onclick="getMoreReviews(<?php echo $course_id.",".($reviewOffset+3);?>);">Load more Reviews ...</a>
</div>
<?php
}
?>