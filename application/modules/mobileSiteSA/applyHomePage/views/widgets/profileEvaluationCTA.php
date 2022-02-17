<?php // cta related key id / code here
	if(!$isCounselorPage){  
?>
<a href="#examScoreWarningLayer" data-rel="dialog" data-transition="slide">
	<button class="gepec-btn btn-primary <?php echo ($position=="top"?"m10":""); ?>" gepecTrackingId="<?php echo $gepecTrackingId;?>" profileEvaluationTrackingId="<?php echo $profileEvaluationTrackingId;?>">Get a free profile evaluation call</button>
</a>
<?php } else{?>
<a href="#examScoreWarningLayer" class="gepec-btn cl-btn" gepecTrackingId="<?php echo $gepecTrackingId;?>" profileEvaluationTrackingId="<?php echo $profileEvaluationTrackingId;?>" data-rel="dialog" data-transition="slide" >Get a free profile evaluation call</a>
<?php } ?>
<a href="#examScoreUploadLayer" data-rel="dialog" data-transition="slide" style="display: none;" id="examScoreUploadAction">&nbsp;</a>