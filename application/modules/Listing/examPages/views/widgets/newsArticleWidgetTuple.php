<?php
	$titleLimit   = 40;
	$summaryLimit = 100;
	if($widgetType == 'news'){
		$summaryLimit = 250;
		$titleLimit   = 58;
	}
	$title   = (strlen(strip_tags($tupledata['blogTitle'])) > $titleLimit ) ? substr(strip_tags($tupledata['blogTitle']), 0, $titleLimit)." ..." : $tupledata['blogTitle'];
	$summary = (strlen(strip_tags($tupledata['summary'])) > $summaryLimit ) ? substr(strip_tags($tupledata['summary']), 0, $summaryLimit)."..." : $tupledata['summary'];

	$summaryCharacterLimitFlag = 0;
	if(strlen(strip_tags($tupledata['summary'])) > $summaryLimit )
		$summaryCharacterLimitFlag = 1;
?>
<div class="expg-slid-tap">
	<?php if($widgetType == 'article'){
	?>
	<img src="<?php echo $tupledata['blogImageURL'] ? str_replace("_s","",MEDIA_SERVER.'/'.$tupledata['blogImageURL']) : MEDIA_SERVER.'/public/images/article_widget_image.jpg'; ?>" height="100" width="240">
	<?php }
	?>
    <div class="expg-slid-box">
    <p class="expg-slid-hd" <?php echo ($widgetType == 'news' ? 'style="height:55px;"': '')?>><a href="<?php echo SHIKSHA_HOME.'/'.$tupledata['url']?>"><?php echo html_escape($title);?></a></p>
    <p class="expg-slid-dt">On <?=date("M d, Y", strtotime($tupledata['lastModifiedDate']))?></p>
    <p class="expg-slid-info" <?php echo ($widgetType == 'news' ? 'style="height:151px;"': '')?>><?php echo html_escape($summary);?>
    <?php if($summaryCharacterLimitFlag){ ?>
    <a href="<?php echo SHIKSHA_HOME.'/'.$tupledata['url']?>">more</a>
    <?php } ?>
    </p>
    </div>
</div>