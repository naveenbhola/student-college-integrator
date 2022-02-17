<div class="newExam-widget downloadExam" id="inlineGuideWidget">
	<p class="top-brdr"></p>
    <p class="bttm-brdr"></p>
    <span class="read-offline">Read it offline</span>
    <a class="dwnldExam-btn dlGuideInline" type="examContent" href="Javascript:void(0);" dlGuideTrackingId = "1048">Email <?php echo strtoupper($examName); ?> Guide</a>
	<?php 
		if($guideDownloadCount >= 50){
			$hideNumber = false;
		}else{
			$hideNumber = true;
		}
	?>
    <span style="<?=($hideNumber)?'display:none;':''?>" class="people-count"><span class="dlCount"><?php echo $guideDownloadCount; ?></span> people downloaded this guide</span>
</div>