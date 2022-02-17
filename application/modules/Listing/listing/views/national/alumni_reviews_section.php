<?php
if(!empty($reviews['reviews_by_email'])) {
?>
<div class="other-details-wrap clear-width alumini-speak-sec">
	<h2 class="mb14">Reviews</h2>
	<div class="scroll-box">
		<div class="rating-box" style="margin-bottom:13px;">
			<p class="rating-title">Overall rating of <?php echo $reviews['institute_name'];?> based on <?php echo count($reviews['reviews_by_email']);?> review<?php echo count($reviews['reviews_by_email']) > 1 ? 's' : '';?></p>
			<ul class="rating-items">
				<?php
				foreach($reviews['ratings'] as $key => $details) {
					if($key == 'Overall Feedback'){
						continue;
					}
					?>
					<li class="clear-width">
						<label><?php echo $key;?> <span>(<?php echo $details['votes'];?> review<?php echo $details['votes'] > 1 ? 's' : ''; ?>)</span></label>
						<div class="rating-cols flLt">
							<div class="rating-child-cols">
							<?php
							for($var=0; $var < round($details['ratings']); $var++) {
							?>
								<i class="sprite-bg star-active-icon"></i>
							<?php
							}
							for($cnt= $var; $cnt < 5; $cnt++){
							?>
							<i class="sprite-bg star-inactive-icon"></i>
							<?php
							}
							?>
						</div>
							<span class="rate-points"><?php echo $details['ratings'];?>/5</span>
							</div>
						
					</li>
					<?php
				}
				?>
			</ul>
			<div class="clearFix"></div>
		</div>
	
		<?php
		if(!empty($reviews['course_names'])) {
		?>
		<div class="view-rating">
			<label>View rating of:</label>
			<select class="universal-select" id="IRS_course_dd" name="IRS_course_dd" onchange="IRS_onchangeCourseDD();">
				<option value="all" selected="selected">All</option>
				<?php
				foreach($reviews['course_names'] as $courseId => $courseName){
				?>
					<option value="<?php echo $courseId;?>"><?php echo $courseName;?></option>
				<?php
				}
				?>
			</select>
		</div>
		<?php
		}
		$reviewCountInFirstPass = 2;
		$emailReviews 	= $reviews['reviews_by_email'];
		$defaultReviews = array_slice($emailReviews , 0, $reviewCountInFirstPass, TRUE);
		?>
		<div id="IRS_review_cont" class="clear-width">
		<?php
		if(!empty($defaultReviews)) {
			foreach($defaultReviews as $review) {
				$keys = array_keys($review['details']);
				if(in_array('Overall Feedback', $keys)){
					$standardKey = 'Overall Feedback';
				} else {
					$standardKey = $keys[0];
				}
				if($standardKey == 'Overall Feedback'){
					$userRatings = $review['details'][$standardKey]['criteria_rating'];
					$reviewRating = round($userRatings, 1);
				} else {
					$userRatings = 0;
					$count = 0;
					foreach($keys as $key){
						$userRatings += $review['details'][$key]['criteria_rating'];
						$count += 1;
					}
					$reviewRating = 0;
					if($count > 0 && $userRatings > 0){
						$reviewRating = round( ($userRatings / $count), 1 );
					}
				}
				
				?>
				<div class="course-review clear-width">
					<div class="course-review-child">
						<label>
							<?php echo $review['details'][$standardKey]['name']; ?>
						</label>
						<p><?php if(!empty($review['details'][$standardKey]['completion_year'])) echo "Class of ". $review['details'][$standardKey]['completion_year'];?> <?php if(!empty($review['details'][$standardKey]['completion_year'])) echo $review['details'][$standardKey]['course_display_name'];?></p>
					</div>
					<div class="rating-cols flRt">
						<div class="rating-child-cols">
							<?php
							for($var=0; $var < round($reviewRating); $var++) {
							?>
							<i class="sprite-bg star-active-icon"></i>
							<?php
							}
							for($cnt=$var; $var < 5; $var++) {
							?>
							<i class="sprite-bg star-inactive-icon"></i>
							<?php
							}
							?>
						</div>
						<span class="rate-points"><?php echo $reviewRating;?>/5</span>
					</div>
				</div>
				<div class="course-review-contents clear-width">
					<?php
					if(!empty($review['details']['Placements']['criteria_desc'])){
					?>
					<p><span>Placements:</span> <?php echo $review['details']['Placements']['criteria_desc'];?></p>
					<?php
					}
					?>
					<?php
					if(!empty($review['details']['Infrastructure / Teaching facilities']['criteria_desc'])){
					?>
					<p><span>Infrastructure / Teaching facilities:</span> <?php echo $review['details']['Infrastructure / Teaching facilities']['criteria_desc'];?></p>
					<?php
					}
					?>
					<?php
					if(!empty($review['details']['Faculty']['criteria_desc'])){
					?>
					<p><span>Faculty:</span> <?php echo $review['details']['Faculty']['criteria_desc'];?></p>
					<?php
					}
					?>
					<?php
					if(!empty($review['details']['Overall Feedback']['criteria_desc'])){
					?>
					<p><span>Overall Feedback:</span> <?php echo $review['details']['Overall Feedback']['criteria_desc'];?></p>
					<?php
					}
					?>
				</div>
				<?php
			}
		}
		?>
		</div>
		<?php
		if(count($reviews['reviews_by_email']) > $reviewCountInFirstPass){
		?>
		<div class="more-review clear-width" id="IRS_pagination_parent">
			<p id="IRS_pagination_cont"><a href="javascript:void(0);" onclick="IRS_loadreviews('all', <?php echo $reviewCountInFirstPass;?>, <?php echo $reviewCountInFirstPass;?>);">See More Reviews</a></p>
		</div>
		<?php
		}
		?>
		<div class="clearFix"></div>
	</div>
</div>

<script>
	var instituteReviews = <?=json_encode($reviews);?>;
</script>
<?php
}
?>