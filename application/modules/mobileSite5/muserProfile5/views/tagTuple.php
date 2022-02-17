    <a href="<?php echo $url; ?>" class="q-card tag" target="" id="myActivity_<?php echo $tag['tagId']; ?>" >
    <div class="q-top">
        <?php if(!empty($tag['activity']) || $tag['activity'] != ''){?><div class="q-heading"><? echo $tag['activity']; ?></div><?php } ?>
        <?php if(!empty($tag['activityTime']) || $tag['activityTime'] != ''){ ?><div class="q-time"><p><i class="clock-ico"></i><?php echo $tag['activityTime']; ?></p></div><?php } ?>
    </div>
    <div class="q-btm">
    	<?php if(!empty($tag['tagName']) || $tag['tagName'] != ''){ ?>
    		<p class="q-qstn"><?php echo $tag['tagName']; ?></p><?php } ?>
        <?php if(!empty($tag['followerCount']) || $tag['followerCount'] != ''){ ?><p class="q-views"><?php echo $tag['followerCount']; ?><span class="txt"><?php if($tag['followerCount'] == 1){echo ' Follower';}else{echo ' Followers';}?></span><?php } ?></p>
    </div> 
    </a>