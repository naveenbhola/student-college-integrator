<?php
	$this->load->view('listing/listingPage/listingHead',array('tab' => 'alumni', 'alumniFeedbackRatingCount' => $alumniFeedbackRatingCount));
	echo jsb9recordServerTime('SHIKSHA_LISTING_DETAIL_ALUMNI_PAGE',1);
?>
<div id="page-contents">
	<div id="listing-left-col">
		<div class="">
			<h2 class="alumina-title">Hear what alumni have to say about their institute collected exclusively on Shiksha.com</h2>
			<div class="flRt pT-8">
				<!-- Google Plus Code -->
					<script type="text/javascript" src="https://apis.google.com/js/plusone.js"></script>
					<span style="float: right; width: 66px;">
						<g:plusone size="medium"></g:plusone>
					</span>
				<!-- Google Plus Code -->
			</div>
		<div class="clearFix"></div>	
		</div>
<?php	
	$types = array('Infrastructure / Teaching facilities','Faculty','Placements','Overall Feedback');
	
	foreach($types as $key=>$type){
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
<div class="desc-details-wrap" style="position:relative">
	<div id="alumnai-<?=($key+1)?>" style="position:absolute;top:-60px;left:0" >&nbsp;</div>
	<div class="title-block">
		<strong><?=$type?></strong>
		<p class="rating-cont">
			<?php
				for($i=0;$i<$finalFeedback;$i++){
					echo '<img src=/public/images/star.gif />';
				}
			?>
			<span><?=$finalFeedback?>/5</span>
		<b>|</b>
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
<div class="desc-details-wrap" id="bottomWidget">
			
</div>
	</div>
	
	<div id="listing-right-col">
		<div id="rightWidget">
		</div>
		<!--<div class="section-cont" id="askWidget">
				
		</div>-->

		<div class="spacer20 clearFix"></div>
		<div id="shikshaAnalytics">
				
		</div>
	</div>
</div>
<?php
$this->load->view('listing/listingPage/listingFoot');
?>
<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion("jquery.bxSlider"); ?>"></script>
<script>
	(function($j) {
		$j('#shikshaAnalytics').load('/listing/ListingPageWidgets/shikshaAnalytics/<?=$institute->getId()?>/0/<?=$pageType?>/'+ "?rand=" + (Math.random()*99999));
		//$j('#askWidget').load('/listing/Listing/getDataForAnAWidget/<?=$institute->getId()?>/institute/alumni');
        //$j('#askWidget').load('/CafeBuzz/ListingPageAnA/getDataForAnAWidget/<?=$institute->getId()?>/alumni');
	})($j);  
</script>
