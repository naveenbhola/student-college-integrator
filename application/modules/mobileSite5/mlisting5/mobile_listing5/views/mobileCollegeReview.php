<?php

$ratingText = array(1=>'Poor',2=>'Below Average',3=>'Average',4=>'Above Average',5=>'Excellent');
?>
<?php if(count($courseReviews[$course->getId()]['reviews']) > 0) { ?>
            <div class="accordian-content clearfix" id='collegeReview'>
            <dl>
                <dt class="ques-title" style="float:left; width:100%; -moz-box-sizing:border-box;-webkit-box-sizing:border-box;box-sizing:border-box;">
                    <span style="font-size:16px !important;font-weight:bold;">College Reviews</span>
                    <div data-enhance="false" class="sorting-option-sec flRt">
                        <label style="position: relative;text-shadow:none;top:1px;">Sort:</label>
                        <select style="font-size:13px;" id="collegeReviewSelect" onchange="sortCollegeReviewsMobile(this,'<?php echo $course->getId();?>');">
                        <?php 
                        $sortCriteria = array("graduationYear" => array('name' => "Year of Graduation", "class" => "active"),
                              "freshness" => array('name' => "Recently Submitted", "class" => "active"),
                              "highestRated" => array('name' => "Highest Rating", "class" => "active"),
                              "lowestRated" => array('name' => "Lowest Rating", "class" => "active")
                        );
                        foreach($sortCriteria as $name => $sortData) { 
                            $selected = "";
                            if($courseReviewDefaultSort == $name) {
                                $selected = "selected='selected'";
                            }
                            ?>
                            <option <?php echo $selected;?> value="<?php echo $name;?>"><?php echo $sortData['name'];?></option>
                        <?php } ?>
                        </select>
                    </div>
                </dt>
                <dd class="alumini-cont">
               	 <div class="clearfix">
                	<div class="flLt avg-rating-title"><strong>Average Course Rating:</strong></div>
                    <div class="ranking-bg" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating"><strong><span itemprop="ratingValue"><?php if(strpos($courseReviews[$course->getId()]['overallRating'],'.')){echo $courseReviews[$course->getId()]['overallRating'];}else{echo $courseReviews[$course->getId()]['overallRating'].'.0';}?></span></strong><sub>/<?php echo $courseReviews[$course->getId()]['ratingCount']; ?> </sub></div>
                 </div>
                 <?php if($courseReviews[$course->getId()]['overallRecommendations'] >= 5) { ?>
                    <div class="recommended-title" style="margin-bottom:0;">
                       <i class="sprite thumb-up-icon"></i>
                       <strong style="display:inline-block; padding-top:3px;"><span itemprop="reviewCount"><?php echo $courseReviews[$course->getId()]['overallRecommendations']; ?></span> Students Recommend This Course</strong>
                   </div>
                <?php }else{
                  echo '<span itemprop="reviewCount" style="display:none;">'.$courseReviews[$course->getId()]['overallRecommendations'].'</span>';
                } ?>
                </dd>
            </dl>
            <div id="collegeReviewLoader"></div>
            <?php $this->load->view('mobileCollegeReviewContent');?>
</div>
<?php } ?>

<script>
    var totalCollegeReviewsCount = <?php echo count($courseReviews[$course->getId()]['reviews']);?>;
</script>
