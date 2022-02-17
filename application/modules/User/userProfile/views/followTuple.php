  <a class="activiy-data userFlw" href="<?php echo $url; ?>" target="" id="myActivity_<?php echo $user['userId']; ?>" >
  <div class="data-head">
    <?php if(!empty($user['activity']) || $user['activity'] != ''){ ?><h1 class="act-h1"><?php echo $user['activity']; ?></h1><?php } ?>
    <?php if(!empty($user['activityTime']) || $user['activityTime'] != ''){ ?><span class="time-slot"><i class=" clock-ico"></i><?php echo $user['activityTime']; ?></span><?php } ?>
  </div>
  <div class="data-options">
    <div class="data-inf">
        <div class="data-img">
          <?php 
            if(empty($user['avtarimageurl']) || strpos($user['avtarimageurl'], 'public/images/photoNotAvailable.gif'))  {
              $intials = substr($user['userName'], 0,1); 
              if(ctype_alpha($intials)){
                $intials = strtoupper($intials);
              }
          ?>
            <?php echo $intials;?>
          <?php } else {?>
            <img src="<?php if(!empty($user['avtarimageurl'])){ echo $user['avtarimageurl']; } ?>" width="60" height="60"></img>
          <?php } ?>
        </div>
        <div class="data-u">
           <?php if(!empty($user['userName']) || $user['userName'] != ''){ ?>
              <p class="d-name"><?php echo $user['userName']; ?></p>
              <?php } ?>
           <?php if(!empty($user['levelName']) || $user['levelName'] != ''){ ?><p class="d-level"><?php echo $user['levelName']; ?></p><?php } ?>
           <?php if(!empty($user['aboutMe']) || $user['aboutMe'] != ''){ ?><p class="d-level"><?php echo $user['aboutMe']; ?></p><?php } ?>
        </div>
          <p class="clr"></p>
    </div>
   </div>
 </a>