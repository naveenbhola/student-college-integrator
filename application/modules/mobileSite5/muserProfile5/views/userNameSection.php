<div class="user-name-detail">
    <input type="hidden" id='headerFlag' />
    <p class="user-name" id='user-name' style="word-wrap:break-word; width: 85%"><?php echo $personalInfo['FirstName'].' '.$personalInfo['LastName']; ?></p>
    <?php if(is_array($userLevelDetails) && !empty($userLevelDetails['levelName'])){ ?>
    <p class="level-txt"><span class="l-txt"  style="word-wrap:break-word"><?php echo $userLevelDetails['levelName']; ?><?php if(!empty($userLevelDetails['points']) || $userLevelDetails['points'] != 0){ ?><span class="q-l"></span></span><span  style="word-wrap:break-word"><?php echo $userLevelDetails['points'].' points'; ?></span> <?php } ?></p>
    <?php } ?>
    
    <p style="word-wrap:break-word;line-height:18px;font-size:12px;margin-bottom:0"><span id='aboutmeHeader'><?php if(!empty($additionalInfo['AboutMe']))echo $additionalInfo['AboutMe'];else if(!$publicProfile) echo'<i>Write something about yourself</i><a href="#pagetwo"><i class="profile-sprite profile-edit-icon personalInfoEditHeader"></i></a>'; ?></span>
    </p>
    <?php if(!$publicProfile){ ?> 
        <div id="editAccountSettings" class="flRt">
            <a href="#pagetwo" class="follow-button">
                <i class="profile-sprite ic_set"></i>
            </a>
        </div>
    <?php }?>
   
</div>

<!-- do not remove below error div -->
<div id='pic_error_div' style ='display:none ' class='error_div_message error_picture_message' >Please upload image file less than 5 MB</div>

<?php 
    $flag_workex = false;
    $workEx = array();
for ($x = 1; $x <= 10; $x++) {
    if($flag_workex){
      break;
    }
    $workExp = 'workExp'.$x;
    $workExp = $$workExp;
    if($workExp['CurrentJob'] == 'YES'){ 
            $flag_workex =true;
    }
    $workEx = $workExp;
}
?>

<ul class="user-detail-list">
    <li id='workExHeader' style="display:<?php if($flag_workex) echo'block'; else echo 'none';?>">
        <i class="profile-sprite user-designation-icon"></i><p id='workExData' style="word-wrap:break-word"><?php echo $workEx['Designation'].' at '.$workEx['Employer']; 
                        ?></p>
    </li>

    <?php $eduFlagHeader = false;
        if($PHD && $PHD['InstituteName']){
          $study = 'PHD from '.$PHD['InstituteName'];
        }else if($PG && $PG['Name'] && $PG['InstituteName']){
          $study = $PG['Name'].' from '.$PG['InstituteName'];
        }else if($UG && $UG['InstituteName'] && $UG['Name']){
          $study = $UG['Name'].' from '.$UG['InstituteName'];
        }else if($twelfth && $twelfth['InstituteName']){
          $study = '12th from '.$twelfth['InstituteName'];
        }else if($tenth && $tenth['InstituteName']){
          $study = '10th from '.$tenth['InstituteName'];
        }
        if($study != ''){
            $eduFlagHeader = true;
        }
    ?>
    <li id='eduBackgroundHeader' style="display:<?php if($eduFlagHeader) echo'block'; else echo 'none';?>">
        <i class="profile-sprite user-degree-icon"></i><p id='educationData' style="word-wrap:break-word"><?php echo $study; ?></p>
    </li>
</ul>
<div class="share-profile-options">

    <?php 
        $flag = false;
        if($socialInfo['FacebookId'] || $socialInfo['TwitterId'] || $socialInfo['LinkedinId'] || $socialInfo['YoutubeId'] || $socialInfo['PersonalURL']){
            $flag = true; 
        }
        ?>
        <div class="flLt">
            <ul>
                <li id='socialProfilesHeader' style="display:<?php if($flag) echo "block"; else echo "none"; ?>">View Profile:</li>

                <?php   if (strpos($socialInfo['FacebookId'], 'http') === false && $socialInfo['FacebookId'] != '') {
                            $socialInfo['FacebookId'] = 'http://'.$socialInfo['FacebookId'];
                        }
                    ?>
                <li style="display:<?php if($socialInfo['FacebookId'] && $socialInfo['FacebookId'] != '') echo 'block'; else echo 'none'; ?>">
                    <a href="<?php echo $socialInfo['FacebookId']; ?>" target="_blank" id='facebookId'>
                        <i class="profile-sprite profile-fb-link"></i>
                    </a>
                </li>
                
                <?php  if (strpos($socialInfo['TwitterId'], 'http') === false && $socialInfo['TwitterId'] != '') {
                        $socialInfo['TwitterId'] = 'http://'.$socialInfo['TwitterId'];
                    }
                ?>
                <li style="display:<?php if($socialInfo['TwitterId'] && $socialInfo['TwitterId'] != '') echo 'block'; else echo 'none'; ?>">
                    <a href="<?php echo $socialInfo['TwitterId']; ?>" target="_blank" id='twitterId'>
                        <i class="profile-sprite profile-twt-link"></i>
                    </a>
                </li>

                <?php  if (strpos($socialInfo['LinkedinId'], 'http') === false && $socialInfo['LinkedinId'] != '') {
                        $socialInfo['LinkedinId'] = 'http://'.$socialInfo['LinkedinId'];
                    }
                ?>
                <li style="display:<?php if($socialInfo['LinkedinId'] && $socialInfo['LinkedinId'] != '') echo 'block'; else echo 'none'; ?>">
                    <a href="<?php echo $socialInfo['LinkedinId']; ?>" target="_blank" id='linkedinId'>
                        <i class="profile-sprite profile-linkdin-link"></i>
                    </a>
                </li>

                <?php if (strpos($socialInfo['YoutubeId'], 'http') === false && $socialInfo['YoutubeId'] != '') {
                        $socialInfo['YoutubeId'] = 'http://'.$socialInfo['YoutubeId'];
                    }
                ?>
                <li style="display:<?php if($socialInfo['YoutubeId'] && $socialInfo['YoutubeId'] != '') echo 'block'; else echo 'none'; ?>">
                    <a href="<?php echo $socialInfo['YoutubeId']; ?>" target="_blank" id='youtubeId'>
                        <i class="profile-sprite profile-youtube-link"></i>
                    </a>
                </li>

                <?php if (strpos($socialInfo['PersonalURL'], 'http') === false && $socialInfo['PersonalURL'] != '') {
                        $socialInfo['PersonalURL'] = 'http://'.$socialInfo['PersonalURL'];
                    }
                ?>
                <li style="display:<?php if($socialInfo['PersonalURL'] && $socialInfo['PersonalURL'] != '') echo 'block'; else echo 'none'; ?>">
                    <a href="<?php echo $socialInfo['PersonalURL']; ?>" target="_blank" id='websiteId'>
                        <i class="profile-sprite profile-web-link"></i>
                    </a>
                </li>

            </ul>
        </div>
    <?php 
    
    if(!$publicProfile){ ?> 
        <div class="flRt">
            <a href="#" class="public-profile-link">View Public Profile</a>
        </div>
    <?php }?> 
</div>