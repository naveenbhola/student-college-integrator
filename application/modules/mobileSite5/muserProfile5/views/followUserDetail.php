<div class="qns-list-card">
    <div id ='qns-avatar' class="q-pic">
    <?php if(empty($avtarimageurl) || strpos($avtarimageurl, 'public/images/photoNotAvailable.gif'))  {

    $intials = substr($userName, 0,1); 
    if(ctype_alpha($intials)){
      $intials = strtoupper($intials);
    }

    ?>
     <?php echo $intials;?>
     
  <?php }else{?>
   
      <img src="<?php if(!empty($avtarimageurl)){ echo $avtarimageurl; } ?>" width="40" height="40"></img>
      <?php } ?>
      </div>
      <div class="qns-dec">
         <?php if(!empty($userName) || $userName != '') { ?>
            <p class="q-n"><a style="color:#676464;" class="userFlw" href="<?php echo $url; ?>" target="" id="myActivity_<?php echo $userId; ?>" ><?php echo $userName; ?></a></p>
          <?php } ?>
         <?php if(!empty($levelName) || $levelName != '') ?><p class="q-l"><?php echo $levelName; ?></p>
         <?php if(!empty($aboutMe) || $aboutMe != '') ?><p class="q-l"><?php echo $aboutMe; ?></p>
      </div>
      <?php if($isUserLoggedIn && $loggedInUserId['userId'] != $userId){ ?>
        <?php if($isUserFollowing == 'true'){ ?>
          <div class="right-btn-col">
            <a href="javaScript:void(0)" class="fl-btn un-flw-btn followUnfollowButton" follow="1" entity='user' iter=<?php echo $iter; ?> followEntityId='<?php echo $userId; ?>'>UNFOLLOW</a>
            <p class="clr"></p>
          </div>
          <?php }else{ ?>
            <div class="right-btn-col">
            <a href="javaScript:void(0)" class="fl-btn followUnfollowButton" follow="0" entity='user' iter=<?php echo $iter; ?> followEntityId='<?php echo $userId; ?>'>FOLLOW</a>
            <p class="clr"></p>
          </div>
          <?php } ?>
          <?php } ?>
      <p class="clr"></p>
   </div>
    <input id='loggedInUserId' type="hidden" value='<?php echo $loggedInUserId['userId']; ?>'>


