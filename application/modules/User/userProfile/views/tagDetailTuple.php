<div class="activiy-data">
	<div class="data-options">
		<?php if(!empty($tagName) || $tagName != ''){ ?>
		<p class="data-p "><a style="color: #4c4c4c;" class="tag" href="<?php echo $url; ?>" target="" id="myActivity_<?php echo $tagId; ?>" ><?php echo $tagName; ?></a></p>
		<?php } ?>
		<p class="straight-txt">
		<?php if(!empty($followerCount) || $followerCount != ''){ ?><b class="data-bold" id="followerCount_<?php echo $iter; ?>"><?php echo $followerCount; ?></b><span id='follower_title_<?php echo $iter; ?>'><?php if($followerCount == 1){echo ' Follower';}else{echo ' Followers';}?></span><?php } ?>
	</p>
	 <?php if($actionNeeded && $loggedInUserId != $userId){ ?>
	 <?php if($isUserFollowing == 'true'){ ?>
		 <div class="last-col">
	           <a href="javaScript:void(0)" class="fl-btn un-flw-btn followUnfollowButton" follow="1" entity='tag' iter=<?php echo $iter; ?> followEntityId='<?php echo $tagId; ?>' >UNFOLLOW</a>
	     </div>
	 <?php }else{ ?>
	 	<div class="last-col">
	           <a href="javaScript:void(0)" class="fl-btn followUnfollowButton" follow="0" entity='tag' iter=<?php echo $iter; ?> followEntityId='<?php echo $tagId; ?>' >FOLLOW</a>
	     </div>
     <?php } } ?>
	</div>
	</div>
    <input id='loggedInUserId' type="hidden" value='<?php echo $loggedInUserId; ?>'>
	