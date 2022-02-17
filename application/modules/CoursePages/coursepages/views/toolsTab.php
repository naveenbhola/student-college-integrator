<?php 
global $collegePredictorExams;
global $rankPredictorExams;
?>
<?php $rankCount = count($rankPredictorExams);
if($rankCount > 0):?>
<div>
	<span class="examResult-layer-title">Rank Predictor</span>
		
		<?php foreach ($rankPredictorExams as $examName => $data):?>
					
					<p style="width:100%;"><a href="<?php echo $data['url'];?>" style="padding:0 !important"><?php echo $data['name'];?></a></p>
		<?php endforeach;?>
</div>
<?php endif;?>
<div class="clearFix"></div>
<?php  $collegeCount = count($collegePredictorExams);
if($collegeCount > 0):?>
<div>
	<span class="examResult-layer-title">College Predictor</span>
		<?php foreach ($collegePredictorExams as $examName => $data):?>
					<p style="width:100%;"><a href="<?php echo $data['url'];?>" style="padding:0 !important"><?php echo $data['name'];?></a></p>
		<?php endforeach;?>
</div>
<?php endif;?>
<div class="clearFix"></div>
<div>
	<span class="examResult-layer-title">Exam Calendar</span>
	<p style="width:100%;"><a href="<?=SHIKSHA_HOME.'/engineering-exams-dates'?>" style="padding:0 !important">Engineering Exam Calendar</a></p>
</div>
<div class="clearFix"></div>
<div>
	<span class="examResult-layer-title">College Reviews</span>
	<p style="width:100%;"><a href="<?=SHIKSHA_HOME.'/engineering-colleges-reviews-cr'?>" style="padding:0 !important">Engineering College Reviews</a></p>
</div>