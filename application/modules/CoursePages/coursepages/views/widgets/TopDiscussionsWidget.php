<?php
$topDiscussions = $widgets_data['topDiscussions'];
if(count($topDiscussions)) {
    if($topDiscussions[0]['commentCount'] == 0) {
	$discussionArray = array();
	foreach($topDiscussions as $key => $discussion) {
	    if($discussion['commentCount'] != 0) {
		$tmpData = $topDiscussions[0];
		array_splice($topDiscussions, $key, 1);
		array_splice($topDiscussions, 0, 1, array($discussion, $tmpData));
		break;
	    }
	}
    }    
?>
<div <?=$cssClass?> id="<?=$widgetObj->getWidgetKey().'Container'?>">
<h2><?=$widgetObj->getWidgetHeading();?></h2><?php
    $count =1;
    foreach($topDiscussions as $key => $discussionInfo) {
	if($discussionInfo['commentCount'] == "") {
		$discussionInfo['commentCount'] = 0;
	}
	
	if($key == 0) {
    ?>    
<div class="news-wrap">
	<div class="comment-cloud">
	<p><span><?=$discussionInfo['commentCount']?></span><br /><?php echo $discussionInfo['commentCount'] > 1 ? 'Comments' : 'Comment';?></p>
    </div>
    <div class="details" style="margin-left:108px">
	<div class="title"><a uniqueattr="CPGS_SESSION_<?php echo $widgetObj->getWidgetKey()?>/<?php echo "position".$count?>" href="<?=$discussionInfo['URL']?>" onclick="gaTrackEventCustom('<?php echo $GA_currentPage;?>','<?php echo $GA_Tap_On_Disc_Link;?>','<?php echo $GA_userLevel;?>');"><?=$discussionInfo['msgTxt']?></a></div>
	<p><?php echo formatArticleTitle($discussionInfo['description'], 100);	?></p>
    </div>
    
</div>	
<ul class="bullet-item">
	<?php } else { ?>	
    <li><a uniqueattr="CPGS_SESSION_<?php echo $widgetObj->getWidgetKey()?>/<?php echo "position".$count?>" href="<?=$discussionInfo['URL']?>" onclick="gaTrackEventCustom('<?php echo $GA_currentPage;?>','<?php echo $GA_Tap_On_Disc_Link;?>','<?php echo $GA_userLevel;?>');"><?=$discussionInfo['msgTxt']?></a>
    <p><?=$discussionInfo['commentCount']?> <?php echo $discussionInfo['commentCount'] > 1 ? 'Comments' : 'Comment';?></p>
    </li>
 <?php
	}
	$count++;
    }
    ?>    
</ul>
<div class="clearFix spacer5"></div>
<div class="tar"><a uniqueattr="CPGS_SESSION_<?php echo $widgetObj->getWidgetKey()?>/<?php echo "View all ".$COURSE_HOME_PAGES_LIST[$courseHomePageId]['Name']." Discussions"?>" href="<?=$allTabsSeoDetails['Discussions']['URL']?>" onclick="gaTrackEventCustom('<?php echo $GA_currentPage;?>','VIEW_ALL_DISCUSSION_COURSEHOMEPAGE_DESKAnA','<?php echo $GA_userLevel;?>');">Views all <?=$COURSE_HOME_PAGES_LIST[$courseHomePageId]['Name'];?> Discussions &raquo;</a></div>
</div>
<?php
}
?>