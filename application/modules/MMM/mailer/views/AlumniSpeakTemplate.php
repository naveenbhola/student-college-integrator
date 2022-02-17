<a class="orange-button" >
<div id="page-contents">
	<div id="listing-left-col">
		<div class="">
			<h2 class="alumina-title">Alumni Speak</h2>
			<div class="flRt pT-8">
			</div>
		<div class="clearFix"></div>	
		</div>
<?php	
	$types = array('Placements','Infrastructure / Teaching facilities','Faculty','Overall Feedback');
	foreach($types as $type){
		if($alumnisReviews->ERROR_MESSAGE == "NO_DATA_FOUND"){
			echo "<h1>No Reviews Yet!</h1>";
			break;
		}
		$totalFeedbacks = array();
		$totalFeedbackValue = 0;
		$totalFeedbackCount = 0;
		foreach($alumnisReviews['alumniReviews'] as $review){
			if(strtolower($review->getCriteriaName()) == strtolower($type)){
				if($review->getCriteriaRating() > 0){
					$totalFeedbacks[] = $review;
					$totalFeedbackValue += $review->getCriteriaRating();
					$totalFeedbackCount += 1;
				}
			}
		}
		$finalFeedback = ceil($totalFeedbackValue/$totalFeedbackCount);
		if($finalFeedback > 0){
?>
<div class="desc-details-wrap">
	<div class="title-block">
		<strong><?=$type?></strong>
		<p class="rating-cont">
			<?php
				for($i=0;$i<$finalFeedback;$i++){
					echo '<img src=/public/images/star.gif />';
				}
			?>
			<span><?=$finalFeedback?>/5</span>
		
		</p>
		<p><span class="sprite-bg alumini-icn"></span> <?=count($totalFeedbacks)?> alumni review(s)</p>
	</div>
	<div class="alumini-content">
	
	
<?php
		foreach($totalFeedbacks as $review){
?>
		<strong class="flLt">
		<?=$review->getName()?>, Class of <?=$review->getCourseComplettionYear()?> - <?=$review->getCourseName()?>
		</strong>
		<div class="flRt">
			<?php
				for($i=0;$i<5;$i++){
					if($i<$review->getCriteriaRating()){
						echo '<img src=/public/images/nlt_str_full.gif />';
					}else{
						echo '<img src=/public/images/nlt_str_blk.gif />';
					}
					
				}
			?> <span><?=$review->getCriteriaRating()?>/5</span>
		</div>
		<div class="spacer5 clearFix"></div>
		<p><?=$review->getCriteriaDescription()?></p>
		<div class="gray-rule"></div>
<?php
			}
?>
	</div>
</div>
<?php			
		}
	}
?>

