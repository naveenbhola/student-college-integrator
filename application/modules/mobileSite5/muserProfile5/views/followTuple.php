
    <a href="<?php echo $url; ?>" class="q-card userFlw" target="" id="myActivity_<?php echo $user['userId']; ?>" >
    <div class="q-top">
        <?php if(!empty($user['activity']) || $user['activity'] != ''){ ?><div class="q-heading"><?php echo $user['activity']; ?></div><?php } ?>
        <?php if(!empty($user['activityTime']) || $user['activityTime'] != ''){ ?><div class="q-time"><p><i class="clock-ico"></i><?php echo $user['activityTime']; ?></p></div><?php } ?>
    </div>
    <div class="q-btm">
        <div class="p-col">
            <div id ='q-pic' class='q-pic'>
            <?php if(empty($user['avtarimageurl']) || strpos($user['avtarimageurl'], 'public/images/photoNotAvailable.gif'))  {
                $intials = substr($user['userName'], 0,1); 
                if(ctype_alpha($intials)){
                    $intials = strtoupper($intials);
                }

            ?>
             <?php echo $intials;?>
            <?php } else {?>

            <img src="<?php if(!empty($user['avtarimageurl'])){ echo $user['avtarimageurl']; } ?>" width="40" height="40"></img>
            <?php } ?>
            </div>
            <div class="q-inf">
                <?php if(!empty($user['userName']) || $user['userName'] != ''){ ?>
                    <p class="q-n"><?php echo $user['userName']; ?></p>
                <?php } ?>
                <?php if(!empty($user['levelName']) || $user['levelName'] != ''){ ?><p class="q-l"><?php echo $user['levelName']; ?></p><?php } ?>
                <?php if(!empty($user['aboutMe']) || $user['aboutMe'] != ''){ ?><p class="q-l"><?php echo $user['aboutMe']; ?></p><?php } ?>
            </div>
            <p class="clr"></p>   
        </div>
    </div> 
    </a>
