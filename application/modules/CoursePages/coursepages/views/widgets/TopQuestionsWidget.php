<?php
$topQuestions = $widgets_data['topQuestions'];

if(count($topQuestions)) {
    if($topQuestions[0]['msgCount'] == 0) {
	$questionArray = array();
	foreach($topQuestions as $key => $question) {
	    if($question['msgCount'] != 0) {
		$tmpData = $topQuestions[0];		
		array_splice($topQuestions, $key, 1);
		array_splice($topQuestions, 0, 1, array($question, $tmpData));
		break;
	    }
	}
    }
?>
<div <?=$cssClass?> id="<?=$widgetObj->getWidgetKey().'Container'?>">
    <div class="top-ques">
    <h3><?=$widgetObj->getWidgetHeading();?></h3>
    <ul class="bullet-item"><?php
    $count = 1;
    foreach($topQuestions as $key => $questionInfo) {
	if($key == 0) {
    ?>    
	<li class="first-item">
	    <div class="ans-cloud">
		<p>
		    <span><?=$questionInfo['msgCount']?></span><br />
		    <?php echo $questionInfo['msgCount'] > 1 ? 'Answers' : 'Answer';?>
		</p>
	    </div>
	    <div class="first-ques"><a uniqueattr="CPGS_SESSION_<?php echo $widgetObj->getWidgetKey()?>/<?php echo "position".$count?>" href="<?=$questionInfo['URL']?>" onclick="gaTrackEventCustom('<?php echo $GA_currentPage;?>','<?php echo $GA_Tap_On_Ques_Link;?>','<?php echo $GA_userLevel;?>');"><?=$questionInfo['msgTxt']?></a></div>
	</li>
    <?php } else { ?>
	<li>
	    <a uniqueattr="CPGS_SESSION_<?php echo $widgetObj->getWidgetKey()?>/<?php echo "position".$count?>" href="<?=$questionInfo['URL']?>" onclick="gaTrackEventCustom('<?php echo $GA_currentPage;?>','<?php echo $GA_Tap_On_Ques_Link;?>','<?php echo $GA_userLevel;?>');"><?=$questionInfo['msgTxt']?></a>
	    <p><?=$questionInfo['msgCount']?> <?php echo $questionInfo['msgCount'] > 1 ? 'Answers' : 'Answer';?></p>
	</li>
    <?php
	}
	$count++;
    } ?>
    </ul>
    <div class="clearFix"></div>
    <div class="tar"><a uniqueattr="CPGS_SESSION_<?php echo $widgetObj->getWidgetKey()?>/<?php echo "View all ".$COURSE_HOME_PAGES_LIST[$courseHomePageId]['Name']." Questions"?>" href="<?=$allTabsSeoDetails['AskExperts']['URL']?>" onclick="gaTrackEventCustom('<?php echo $GA_currentPage;?>','VIEW_ALL_DISCUSSION_COURSEHOMEPAGE_DESKAnA','<?php echo $GA_userLevel;?>');">View all <?=$COURSE_HOME_PAGES_LIST[$courseHomePageId]['Name'];?> Questions &raquo;</a></div>
    </div>
</div>    
<?php
}
?>