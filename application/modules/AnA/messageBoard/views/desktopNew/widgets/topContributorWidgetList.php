<?php if($rightWidgetUserData) {
  switch ($trackingPageKeyId) {
    case 858:
    case 865:
    case 869:
        $GA_currentPage     = 'HOMEPAGE_DESKAnA';
        $GA_Tap_On_User     = 'MOSTACTIVE_PROFILE_RHSWIDGET_HOMEPAGE_DESKAnA';
        $GA_Tap_on_Follow   = 'MOSTACTIVE_FOLLOW_RHSWIDGET_HOMEPAGE_DESKAnA';
        break;
    case 888:
    case 887:
        $GA_currentPage     = 'QUESTION DETAIL PAGE';
        $GA_Tap_On_User     = 'MOSTACTIVE_PROFILE_RHSWIDGET_QUESTIONDETAIL_DESKAnA';
        $GA_Tap_on_Follow   = 'MOSTACTIVE_FOLLOW_RHSWIDGET_QUESTIONDETAIL_DESKAnA';
        break;
    case 892:
    case 893:
        $GA_currentPage     = 'DISCUSSION DETAIL PAGE';
        $GA_Tap_On_User     = 'MOSTACTIVE_PROFILE_RHSWIDGET_DISCUSSIONDETAIL_DESKAnA';
        $GA_Tap_on_Follow   = 'MOSTACTIVE_FOLLOW_RHSWIDGET_DISCUSSIONDETAIL_DESKAnA';
        break;
    case 847:
    case 831:
    case 848:
    case 838:
    case 849:
    case 842:
        $GA_currentPage     = 'TAG DETAIL PAGE';
        $GA_Tap_On_User     = 'MOSTACTIVE_PROFILE_RHSWIDGET_TAGDETAIL_DESKAnA';
        $GA_Tap_on_Follow   = 'MOSTACTIVE_FOLLOW_RHSWIDGET_TAGDETAIL_DESKAnA';
        break;
    case 876:
        $GA_currentPage     = 'ALLQUEST_DESKAnA';
        $GA_Tap_On_User     = 'MOSTACTIVE_PROFILE_RHSWIDGET_ALLQUEST_DESKAnA';
        $GA_Tap_on_Follow   = 'MOSTACTIVE_FOLLOW_RHSWIDGET_ALLQUEST_DESKAnA';
        break;
    case 883:
        $GA_currentPage     = 'ALLDISC_DESKAnA';
        $GA_Tap_On_User     = 'MOSTACTIVE_PROFILE_RHSWIDGET_ALLDISC_DESKAnA';
        $GA_Tap_on_Follow   = 'MOSTACTIVE_FOLLOW_RHSWIDGET_ALLDISC_DESKAnA';
        break;
  }
  $viewPortHeight = "height:310px;";
  if(count($rightWidgetUserData) < 3){
    $viewPortHeight = "height:".(145*count($rightWidgetUserData))."px;";
  }
?>
<div class="panel-head">
     <p class="headp-p"><?php echo $rightData['topHeading1'];?><span><?php echo $rightData['topHeading2'];?></span></p>
     <span><?php echo $rightData['subHeading'];?></span>
   </div>
   <div class="u-list" id="rightUsersWidget">
<div class="scrollbar"><div class="track">
<div class="thumb"><div class="end"></div></div></div></div>
<div class="viewport" style="<?php echo $viewPortHeight;?>">
<div class="overview" style="top: 0px;">
<ul id="rightWidgetList">
  <?php 
    foreach ($rightWidgetUserData as $userDataObj) {
      if($userDataObj['userId'] != $userId)
        $userProfileURL = getSeoUrl($userDataObj['userId'],'userprofile');
      else
        $userProfileURL = SHIKSHA_HOME."/userprofile/edit";
  ?>
   <li class="cta-block">
         <div class="c-block">
         <?php 
        if($userDataObj['userId'] != $userId){
          if($userDataObj['isFollowing'] == 1 || $userDataObj['isFollowing'] === 'true' || $userDataObj['isFollowing'] === '1'){ ?>
         <a href="javascript:void(0);" class="ana-btns un-btn" onclick="gaTrackEventCustom('<?php echo $GA_currentPage;?>','<?php echo $GA_Tap_on_Follow;?>','<?php echo $GA_userLevel;?>');followEntity(this,<?php echo $userDataObj['userId'];?>,'user',false,'<?php echo $trackingPageKeyId;?>')" reverseclass="f-btn" curclass="un-btn" callforaction="unfollow">Unfollow</a>
         <?php } else { ?>
         <a href="javascript:void(0);" class="ana-btns f-btn" onclick="gaTrackEventCustom('<?php echo $GA_currentPage;?>','<?php echo $GA_Tap_on_Follow;?>','<?php echo $GA_userLevel;?>');followEntity(this,<?php echo $userDataObj['userId'];?>,'user',false,'<?php echo $trackingPageKeyId;?>')" reverseclass="un-btn" curclass="f-btn" callforaction="follow">Follow</a>
         <?php } 
		}
		?>
               <a class="new-avatar" href="<?=$userProfileURL;?>" onclick="gaTrackEventCustom('<?php echo $GA_currentPage;?>','<?php echo $GA_Tap_On_User;?>','<?php echo $GA_userLevel;?>');">
                 <?php if($userDataObj['avtarimageurl'] != '' && strpos($userDataObj['avtarimageurl'],'/public/images/photoNotAvailable.gif') === false){?>
                    <img src=<?php echo getSmallImage($userDataObj['avtarimageurl']);?> alt="Shiksha Ask & Answer" style="width: 60px;height: 60px;">
                <?php } else{
                    echo ucwords(substr($userDataObj['name'],0,1));
                }?>
               </a>
                  <div class="c-inf">
                  <?php if(strlen($userDataObj['name']) > 30)
                  {
                    $userName = substr($userDataObj['name'], 0,26).'...';
                  }
                  else
                    $userName = $userDataObj['name'];
                  ?>
                     <a href="<?=$userProfileURL;?>" class="avatar-name" onclick="gaTrackEventCustom('<?php echo $GA_currentPage;?>','<?php echo $GA_Tap_On_User;?>','<?php echo $GA_userLevel;?>');"><?php echo $userName;?></a>
                     <div class="l-div">
                        <p class="des-p"><?php echo $userDataObj['levelName'];?> 
                        <?php if(isset($userDataObj['weekPoints'])){ ?>
                        <span>| Points This week <?php echo $userDataObj['weekPoints'];?></span>
                        <?php } ?>
                        </p>
                        <p class="c-level"><?php echo $userDataObj['aboutMe'];?></p>
                     </div>
                  </div>
               <div class="btm-div">
                   <div class="t-cell">
                     <span style="display:none;" class="followersCountTextArea" curClass="" revClass=""><?php echo $userDataObj['followers'] ? $userDataObj['followers'] : 0;?></span>
                     <p>Followers <b class="followCnt"><?php echo $userDataObj['followers'] ? formatNumber($userDataObj['followers']) : 0;?></b></p>
                   </div>
                    <div class="t-cell">
                     <p class="">Answers <b><?php echo $userDataObj['answerCount'] ? formatNumber($userDataObj['answerCount']) : 0;?></b></p>
                   </div>
                    <div class="t-cell">
                     <p class="">Upvotes <b><?php echo $userDataObj['upvoteCount'] ? formatNumber($userDataObj['upvoteCount']) : 0;?></b></p>
                   </div>
               </div>   
                  
            </div>
   </li>
   <?php } ?>
</ul>
</div>
</div>
</div><?php } ?>
