<?php
	if($extraParams['entityType']=="user" || $extraParams['entityType']=="question"){
	  $displayStr = "Question";
	  $seoUrl = getSeoUrl($extraParams['threadId'],'question',$extraParams['msgTxt']);
	}
	else if($extraParams['entityType']=="discussion" ){
	  $displayStr = "Discussion";
	  $seoUrl = getSeoUrl($extraParams['threadId'],'discussion',$extraParams['msgTxt']);
	}
	else if($extraParams['entityType']=="announcement" ){
	  $displayStr = "Annoucement";
	  $seoUrl = getSeoUrl($extraParams['threadId'],'announcement',$extraParams['msgTxt']);
	}
	else if($extraParams['entityType']=="eventAnA" ){
	  $displayStr = "Event";
	  $seoUrl = getSeoUrl($extraParams['threadId'],'question',$extraParams['msgTxt']);
	}
	else if($extraParams['entityType']=="review" ){
	  $displayStr = "College Review";
	  $seoUrl = getSeoUrl($extraParams['threadId'],'question',$extraParams['msgTxt']);
	}
?>
<p>Hi,</p>
<p><a href="<?php echo SHIKSHA_HOME."/getUserProfile/".$extraParams['NameOFUser'];?>"><?php echo $extraParams['NameOFUser'];?></a> thinks the following <?php echo $entityType;?> on Shiksha Caf&#233; is interesting and has shared the same with you:</p>
<p><b><?php echo $displayStr;?></b>: <?php echo nl2br($extraParams['msgTxt']);?></p>
<p>Please <a href="<?php echo $seoUrl;?>">click here</a> to visit the complete thread.</p>

<p><a href="<?php echo SHIKSHA_ASK_HOME;?>">Shiksha Caf&#233;</a> is a platform wherein you can engage actively with fellow students. You will not only benefit immensly by discovering latest trends and popular career options but you&#39;ll also enjoy sharing your bit of knowledge.</p>

<p>Best Regards<br/>
Shiksha.com team</p>