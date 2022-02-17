<?php 
      $GA_userLevel = $loggedInUser > 0 ? 'Logged In':'Non-Logged In';
      if($threadType == 'question')
      {
          $GA_PageName = 'QUESTION DETAIL PAGE';
          if($actionType == 'upvote')
          {
            $GA_TapOnFollow = 'FOLLOW_UPVOTERS_ANSWER_QUESTIONDETAIL_WEBAnA';
            $GA_TapOnUserProfile = 'PROFILE_UPVOTERS_ANSWER_QUESTIONDETAIL_WEBAnA';
          }
          else if($actionType == 'follow')
          {
            $GA_TapOnFollow = 'FOLLOW_FOLLOWERLIST_QUESTIONDETAIL_WEBAnA';
            $GA_TapOnUserProfile = 'PROFILE_FOLLOWERLIST_QUESTIONDETAIL_WEBAnA';
          }
      }
      else if($threadType == 'discussion')
      {
          $GA_PageName = 'DISCUSSION DETAIL PAGE';
          if($actionType == 'upvote')
          {
            $GA_TapOnFollow = 'FOLLOW_UPVOTERS_COMMENT_DISCUSSIONDETAIL_WEBAnA';
            $GA_TapOnUserProfile = 'PROFILE_UPVOTERS_COMMENT_DISCUSSIONDETAIL_WEBAnA';
            
          }
          elseif($actionType == 'follow')
          {
            $GA_TapOnFollow = 'FOLLOW_FOLLOWERLIST_DISCUSSIONDETAIL_WEBAnA';
            $GA_TapOnUserProfile = 'PROFILE_FOLLOWERLIST_DISCUSSIONDETAIL_WEBAnA';
          }
      }
?>
<?php foreach($userListDetails as $key=>$userDetail){?>     
        <div class="upvote-card" onclick="gaTrackEventCustom('<?php echo $GA_PageName;?>','<?php echo $GA_TapOnUserProfile;?>','<?php echo $GA_userLevel;?>');window.location='<?=$userDetail['url']?>'">
            <div class="upvote-dtls">
             <div class="upvote-img">
             <?php 

                  if($userDetail['avtarimageurl'] != '' && strpos($userDetail['avtarimageurl'],'/public/images/photoNotAvailable.gif') === false){?>
                      <img src=<?php echo getSmallImage($userDetail['avtarimageurl']);?> alt="Shiksha Ask & Answer" style="width: 45px;height: 45px;">

                  <?php }else{
                      echo ucwords(substr($userDetail['userName'],0,1));
             }?>
                </div>
                <div class="upvote-desc">
                    <?php if(strlen($userDetail['userName'])>15){
                        $userName = substr($userDetail['userName'],0,15).'...';
                    }else{
                        $userName = $userDetail['userName'];
                    } ?>
                    <a class="upvote-name" ><?=$userName?></a>
                    <p class="user-level"><?=$userDetail['levelName']?></p>
                    <p class="user-level"><?=$userDetail['aboutMe']?></p>
                </div>
            </div>

            <?php if($userDetail['userId'] != $loggedInUser) {?>
            <div class="upvote-col">
                <?php if($userDetail['isUserFollowing'] == 'true'){ ?>
                          <a class="ut-flw-btn" reverseclass="ut-btn" callforaction="unfollow" href="javascript:void(0)" onclick="gaTrackEventCustom('<?php echo $GA_PageName;?>','<?php echo $GA_TapOnFollow;?>','<?php echo $GA_userLevel;?>');followEntity(this,<?php echo $userDetail['userId'];?>,'user',false,'<?php echo $followTrackingPageKeyId;?>');preventOnclickFollow(event);">UNFOLLOW
                    <?php }else{?>
                          <a class="ut-btn" reverseclass="ut-flw-btn" callforaction="follow" href="javascript:void(0)" onclick="gaTrackEventCustom('<?php echo $GA_PageName;?>','<?php echo $GA_TapOnFollow;?>','<?php echo $GA_userLevel;?>');followEntity(this,<?php echo $userDetail['userId'];?>,'user',false,'<?php echo $followTrackingPageKeyId;?>');preventOnclickFollow(event);">FOLLOW
                    <?php } ?>
                    </a>
                <p class="clr"></p>
            </div>
            <?php } ?>
        </div>
        
<?php } ?>
<div data-enhance="false">
     <input type="hidden" name="startUserList" id="startUserList" value="<?php echo $startIndex;?>" />
     <input type="hidden" name="countUserList" id="countUserList" value="<?php echo $countIndex;?>" />

</div>
<div style="text-align: center; margin-top: 7px; margin-bottom: 10px; display:none;" id="loader-id">
     <img border="0" alt="" id="loadingImage1" class="small-loader" style="border-radius:50%" src="//<?php echo IMGURL; ?>/public/mobile5/images/ShikshaMobileLoader.gif">
</div>

 <script>
  var pageNoUserList = <?php echo $pageNoUserList;?>;
  
</script>
    