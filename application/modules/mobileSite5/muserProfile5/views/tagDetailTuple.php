<div class="qns-list-card">
    <div class="qns-dec">
        <?php if(!empty($tagName) || $tagName != '') ?>
        <p class="q-qstn"><a style="color:#676464;" class="tag" href="<?php echo $url; ?>" target="" id="myActivity_<?php echo $tagId; ?>" ><?php echo $tagName; ?></a></p>
        <?php if(!empty($followerCount) || $followerCount != ''){ ?><p  id='flwrcount_<?php echo $iter; ?>' class="q-views"><span id="followerCount_<?php echo $iter; ?>"><?php echo $followerCount; ?></span><span class="txt"><?php if($followerCount == 1){echo ' Follower';}else{echo ' Followers';} ?></span><?php } ?></p>
    </div>
    <?php if($isUserLoggedIn && $loggedInUserId['userId'] != $userId){ ?>
    <?php if($isUserFollowing == 'true'){ ?>
    <div class="right-btn-col">
    	<a href="javaScript:void(0)" class="fl-btn un-flw-btn followUnfollowButton" follow="1" entity='tag' iter=<?php echo $iter; ?> followEntityId='<?php echo $tagId; ?>' >UNFOLLOW</a>
    	<p class="clr"></p>
    </div>
    <?php }else{ ?>
    <div class="right-btn-col">
        <a href="javaScript:void(0)" class="fl-btn followUnfollowButton" follow="0" entity='tag' iter=<?php echo $iter; ?> followEntityId='<?php echo $tagId; ?>'>FOLLOW</a>
        <p class="clr"></p>
      </div>
    <?php } ?>
    <?php } ?>
    <p class="clr"></p>
</div>
    <input id='loggedInUserId' type="hidden" value='<?php echo $loggedInUserId['userId']; ?>'>
