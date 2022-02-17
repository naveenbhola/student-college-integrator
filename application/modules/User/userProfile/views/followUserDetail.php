
  <div class="activiy-data">
  <div class="data-options">
    <div class="data-inf">
        <div class="data-img">
          <?php 
            if(empty($avtarimageurl) || strpos($avtarimageurl, 'public/images/photoNotAvailable.gif'))  {
              $intials = substr($userName, 0,1); 
              if(ctype_alpha($intials)){
                $intials = strtoupper($intials);
              }
          ?>
            <?php echo $intials;?>
          <?php } else {?>
            <img src="<?php if(!empty($avtarimageurl)){ echo $avtarimageurl; } ?>" width="60" height="60"></img>
          <?php } ?>
        </div>
        <div class="data-u">
           <?php if(!empty($userName) || $userName != ''){ ?>
              <p class="d-name"><a style="color: #4c4c4c;" class="userFlw" href="<?php echo $url; ?>" target="" id="myActivity_<?php echo $userId; ?>" ><?php echo $userName; ?></a></p>
           <?php } ?>
           <?php if(!empty($levelName) || $levelName != ''){ ?><p class="d-level"><?php echo $levelName; ?></p><?php } ?>
           <?php if(!empty($aboutMe) || $aboutMe != ''){ ?><p class="d-level"><?php echo $aboutMe; ?></p><?php } ?>
        </div>
        <?php if($actionNeeded && $loggedInUserId != $userId){ ?>
        <div class="last-col">
          <?php if($isUserFollowing == 'true'){ ?>
           <a href="javaScript:void(0)" class="fl-btn un-flw-btn followUnfollowButton" follow="1" entity='user' iter=<?php echo $iter; ?> followEntityId='<?php echo $userId; ?>'>UNFOLLOW</a>
          <?php }else if($isUserFollowing == 'false'){ ?>
            <a href="javaScript:void(0)" class="fl-btn followUnfollowButton" follow="0" entity='user' iter=<?php echo $iter; ?> followEntityId='<?php echo $userId; ?>'>FOLLOW</a>
          <?php } ?>
        </div>
        <?php } ?>
          <p class="clr"></p>
    </div>
   </div>
 </div>
    <input id='loggedInUserId' type="hidden" value='<?php echo $loggedInUserId; ?>'>
 