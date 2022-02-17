
	<a href="<?php echo $url; ?>" class="activiy-data tag" target="" id="myActivity_<?php echo $tag['tagId']; ?>" >	
	<div class="data-head">
		<?php if(!empty($tag['activity']) || $tag['activity'] != ''){?><h1 class="act-h1"><? echo $tag['activity']; ?></h1><?php } ?>
		<?php if(!empty($tag['activityTime']) || $tag['activityTime'] != ''){ ?><span class="time-slot"><i class=" clock-ico"></i><?php echo $tag['activityTime']; ?></span><?php } ?>
	</div>
	<div class="data-options">
		<?php if(!empty($tag['tagName']) || $tag['tagName'] != ''){ ?>
			<p class="data-p"><?php echo $tag['tagName']; ?></p>
		<?php } ?>
		<p class="straight-txt">
		<?php if(!empty($tag['followerCount']) || $tag['followerCount'] != ''){ ?><b class="data-bold"><?php echo $tag['followerCount']; ?></b><?php if($tag['followerCount'] == 1){echo ' Follower';}else{echo ' Followers';}?><?php } ?>
	</p>
	</div>
	</a>
