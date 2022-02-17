<?php
$typeQoD = "";
if($pageType == "questions"){
	$placeholder="What is your question?";
	$stickyTrackingPageKeyId = 960;	
	$typeQoD = "question";
}
/*else if($pageType == "questions" && $contentType=='discussion'){
	$placeholder="Start a new discussion";
	$stickyTrackingPageKeyId = $dstickyTrackingPageKeyId;
	$typeQoD = "discussion";
}*/
else{
	return;
}
?>
<div class="input-tab-wrap" data-enhance="false" id="stickyAskCTA" style="display:none;">
	<a class="sticky-ask" href="#questionPostingLayerOneDiv" data-inline="true" data-rel="dialog" data-transition="fade" onclick="populateQuesDiscLayer('<?=$typeQoD;?>','<?php echo $stickyTrackingPageKeyId;?>');">
		<span><?php echo $placeholder;?></span>
	</a>
</div>