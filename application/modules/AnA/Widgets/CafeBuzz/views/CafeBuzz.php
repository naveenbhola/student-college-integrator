<?php
if(is_array($topicListings) && isset($topicListings['results'])){
$questionList = $topicListings['results'];
$levelVCard = $topicListings['levelVCard'];
$categoryCountry = $topicListings['categoryCountry'];

$displayEntries = 2;
$totalCount = count($questionList);
$showCount = count($questionList) + 1;

//Code Start to create VCard, User level, Category and Country arrays with UserId as Index
$VCardArray = array();
$userLevel = array();
$userLevelParticipate = array();
for($i=0;$i<count($levelVCard);$i++){
  $userIdTemp = $levelVCard[$i]['userid'];
  $VCardArray[$userIdTemp] = $levelVCard[$i]['vcardStatus'];
  $userLevel[$userIdTemp] = $levelVCard[$i]['ownerLevel'];
  $userLevelParticipate[$userIdTemp] = $levelVCard[$i]['ownerLevelP'];
}
$CategoryCountryArray = array();
for($i=0;$i<count($categoryCountry);$i++){
  $threadIdTemp = $categoryCountry[$i]['msgId'];
  $CategoryCountryArray[$threadIdTemp]['category'] = $categoryCountry[$i]['category'];
  $CategoryCountryArray[$threadIdTemp]['categoryId'] = $categoryCountry[$i]['categoryId'];
  $CategoryCountryArray[$threadIdTemp]['country'] = $categoryCountry[$i]['country'];
  $CategoryCountryArray[$threadIdTemp]['countryId'] = $categoryCountry[$i]['countryId'];
}
//Code End to create VCard, User level, Category and Country arrays with UserId as Index
//Code Start to merge the Result and the Level advance arrays
$mainArray = array();
$x=0;
for($j=0;$j<count($questionList);$j++){
  $mainArray[$x] = $questionList[$j];
  $x++;
}
uasort($mainArray, 'cmp');

//Code End to merge the Result and the Level advance arrays
$x=0;
foreach($mainArray as $resultBlock){
  if($x<10)
    $questionList[$x] = $resultBlock;
  $x++;
}

$userId = isset($validateuser[0]['userid'])?$validateuser[0]['userid']:0;
$userImageURLDisplay = isset($validateuser[0]['avtarurl'])?$validateuser[0]['avtarurl']:'/public/images/photoNotAvailable.gif';

$userProfile = site_url('getUserProfile').'/';
$displayName = isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:'';

function cmp($a,$b)
{
  if ($a['sortingTime'] == $b['sortingTime']) return 0;
  return ($a['sortingTime'] < $b['sortingTime']) ? 1 : -1;  
}

function getDisplayNameText($userId, $entityUserId, $displayName, $vcardStatus)
{
    $userProfile = site_url('getUserProfile').'/';
    /*if($entityUserId==$userId){
          $userDisplayText = '<span onmouseover="javascript:try{showUserCommonOverlayForCard(this,'.$entityUserId.');}catch(e){}" style="width:30px;display:inline;" ><a href="'.$userProfile.$displayName.'">'.$displayName.'</a></span>&nbsp;<span title="click to change your display name" style="cursor:pointer;" onClick="showUpdateUserNameImage(\'Edit your display name here\',\'\',\'\',\'\',0);"><img src="/public/images/fU.png" /></span>';
    }else{
          $userDisplayText = '<span onmouseover="javascript:try{showUserCommonOverlayForCard(this,'.$entityUserId.');}catch(e){}" style="width:30px;display:inline;" ><a href="'.$userProfile.$displayName.'">'.$displayName.'</a></span>';
    }*/
    $userDisplayText = '<a href="'.$userProfile.$displayName.'">'.$displayName.'</a>';
    return $userDisplayText;
}

function getTheRatingStarClass($level)
{
  $html = '';
  $level = trim($level);
  switch($level){
    case 'Advisor': $html = "str_1s";$value="Advisor";break;
    case 'Senior Advisor': $html = "str_2s";$value="Senior Advisor";break;
    case 'Lead Advisor': $html = "str_3s";$value="Lead Advisor";break;
    case 'Principal Advisor': $html = "str_4s";$value="Principal Advisor";break;
    case 'Chief Advisor': $html = "str_5s";$value="Chief Advisor";break;
  }
  return array('html'=>$html,'value'=>$value);
}

function getIconClass($type)
{
    $class = "";
    if($type!=''){
    switch($type){
      case 'question': $class = "";break;
      case 'discussion': $class = "ana_blog";break;
      case 'announcement': $class = "ana_mike";break;
      case 'discussioncomment': $class = "ana_blog";break;
      case 'announcementcomment': $class = "ana_mike";break;
      default: $class = "ana_q";break;
    }
    }
    return $class;
}

$commentsDisplayArray = array('comment','discussion','discussioncomment','announcement','announcementcomment','review','reviewcomment','eventAna','eventAnAcomment');
$newEntityDisplayArray = array('discussion','discussioncomment','announcement','announcementcomment','review','reviewcomment','eventAna','eventAnAcomment');
$newCommentEntityDisplayArray = array('discussioncomment','announcementcomment','reviewcomment','eventAnAcomment');
$newEntityArray = array('discussioncomment'=>'discussion','announcementcomment'=>'announcement','discussion'=>'discussion','announcement'=>'announcement','review'=>'review','reviewcomment'=>'review','eventAnA'=>'eventAnA','eventAnAcomment'=>'eventAnA');
?>

<div class="cafeBuzzBlock">
<div class="cafeBuzzTitle">
    <div class="cafeBuzzIcon"></div>
    <h2>Cafe Buzz! <br />
    <span>Latest from Shiksha Cafe</span>
    </h2>
</div>

<div class="spacer10 clearFix"></div>

<script>
currentPageName = "ANA WALL";
</script>
<?php $homeUrl = $categoryId > 1 ? SHIKSHA_ASK_HOME_URL.'/messageBoard/MsgBoard/discussionHome/'.$categoryId.'/0/1/answer/' : SHIKSHA_ASK_HOME; ?>
<!-- Top Div Start -->
<div>
<div style="display:none;" id="abuseFormText"></div>
<div class="lineSpace_5"></div>
<!-- Container Div Start -->
<div style="position:relative;height:140px;overflow:hidden;cursor:pointer;" onClick="window.location='<?php echo $homeUrl;?>';">
<?php
for($i=0;$i<count($questionList);$i++)
{
    $showCount--;
    if($questionList[$i]['type']!='level'){	//If for Level type

    $questionUrl = $questionList[$i][0]['url'];
    $ansNowLink = "";
    $inlineFormHtml = "";
    if($questionList[$i][0]['status'] == 'closed'){
        $ansNowLink = '<span class="normaltxt_11p_blk ">This question has been closed for answering.</span>';
    }
    if(isset($questionList[$i][0]['alreadyAnswered']) && ($questionList[$i][0]['alreadyAnswered'] > 0)){
        $ansNowLink = '<div class="normaltxt_11p_blk "><img src="/public/images/greenChk.gif" align="absmiddle" />&nbsp;<a href="'.$questionUrl.'"  style="color:#000000;">You have already answered this question</a></div>';
    }
    
    $questionUserDisplayName = $questionList[$i][0]['displayname'];
    $questionUserId = $questionList[$i][0]['userId'];
    if( in_array($questionList[$i]['type'],$newEntityDisplayArray)){
        $questionUserLevel = '';
    }
    else{
        $questionUserLevel = getTheRatingStar($userLevel[$questionUserId]);
    }
    $questionVCardStatus = $VCardArray[$questionUserId];
    $questionText = $questionList[$i][0]['msgTxt'];
    $questionMsgId = $questionList[$i][0]['msgId'];
    if($questionList[$i]['type'] == 'question'){
        $userImageURL = $questionList[$i][0]['userImage'];
        $userImageURLOwnerId = $questionList[$i][0]['userId'];
        $questionFontStyle = "style='color:#000000'";
        $questionFontClass = "class='grayFont  Fnt11'";
    }
    else if(in_array($questionList[$i]['type'],$newEntityDisplayArray)){
        $userImageURL = $questionList[$i][0]['userImage'];
        $userImageURLOwnerId = $questionList[$i][0]['userId'];
        $questionFontStyle = "style='color:#000000'";
        $questionFontClass = "class='grayFont  Fnt11'";
        $answerMsgId = $questionList[$i][0]['msgId'];
    }
    else
    {
        $userImageURL = $questionList[$i][1]['userImage'];
        $userImageURLOwnerId = $questionList[$i][1]['userId'];
        $answerUserDisplayName = $questionList[$i][1]['displayname'];
        $answerUserId = $questionList[$i][1]['userId'];
        if( in_array($questionList[$i]['type'],$newEntityDisplayArray)){
            $answerUserLevel = '';
        }
        else{
            $answerUserLevel = getTheRatingStar($userLevel[$answerUserId],$questionList[$i]['type']);
        }
        $answerVCardStatus = $VCardArray[$answerUserId];
        $answerText = $questionList[$i][1]['msgTxt'];
        $answerMsgId = $questionList[$i][1]['msgId'];
        $questionFontStyle = "style='color:#707070'";
        $questionFontClass = "class='grayFont  Fnt11'";
    }
    $answerFontStyle = "style='color:#000000'";
    $answerFontClass = "class='grayFont Fnt11'";
    if($questionList[$i]['type'] == 'rating'){
        $userImageURL = $questionList[$i][2]['userImage'];
        $userImageURLOwnerId = $questionList[$i][2]['userId'];
        $raterUserDisplayName = $questionList[$i][2]['displayname'];
        $raterUserId = $questionList[$i][2]['userId'];
        if( in_array($questionList[$i]['type'],$newEntityDisplayArray)){
            $raterUserLevel = '';
        }
        else{
            $raterUserLevel = getTheRatingStar($userLevel[$raterUserId]);
        }
        $raterVCardStatus = $VCardArray[$raterUserId];
    }
    else if($questionList[$i]['type'] == 'comment' || in_array($questionList[$i]['type'],$newCommentEntityDisplayArray)){
        $commentNumberToDisplay = count($questionList[$i])-3;
        $userImageURL = $questionList[$i][$commentNumberToDisplay]['userImage'];
        $userImageURLOwnerId = $questionList[$i][$commentNumberToDisplay]['userId'];
        $commenterUserDisplayName = $questionList[$i][$commentNumberToDisplay]['displayname'];
        $commenterUserId = $questionList[$i][$commentNumberToDisplay]['userId'];
        if( in_array($questionList[$i]['type'],$newEntityDisplayArray)){
            $commenterUserLevel = '';
        }
        else{
            $commenterUserLevel = getTheRatingStar($userLevel[$commenterUserId]);
        }
        $userCommentTimeDisplayNumber = $commentNumberToDisplay;
        $commenterVCardStatus = $VCardArray[$commenterUserId];
        $answerFontStyle = "style='color:#707070'";
        $answerFontClass = "class='grayFont  Fnt11'";
    }

    /*if($questionUserId==$userId){
          $questionUserDisplayTextForHeading = '<span onmouseover="javascript:try{showUserCommonOverlayForCard(this,'.$questionUserId.');}catch(e){}" style="width:30px;display:inline;" ><a href="'.$userProfile.$questionUserDisplayName.'">'.$questionUserDisplayName.'\'s</a></span>&nbsp;<span title="click to change your display name" style="cursor:pointer;" onClick="showUpdateUserNameImage(\'Edit your display name here\',\'\',\'\',\'\',0);"><img src="/public/images/fU.png" /></span>';
    }else{
          $questionUserDisplayTextForHeading = '<span onmouseover="javascript:try{showUserCommonOverlayForCard(this,'.$questionUserId.');}catch(e){}" style="width:30px;display:inline;" ><a href="'.$userProfile.$questionUserDisplayName.'">'.$questionUserDisplayName.'\'s</a></span>';
    }*/
    $questionUserDisplayTextForHeading = '<a href="'.$userProfile.$questionUserDisplayName.'">'.$questionUserDisplayName.'\'s</a>';
    $questionUserDisplayText = getDisplayNameText($userId, $questionUserId, $questionUserDisplayName, $questionVCardStatus);
    $questionUserLevelText = '<span class=\'forA \'><a href="/shikshaHelp/ShikshaHelp/upsInfo">'.$questionUserLevel.'</a></span>';
    /*if($answerUserId==$userId){
          $answerUserDisplayTextForHeading = '<span onmouseover="javascript:try{showUserCommonOverlayForCard(this,'.$answerUserId.');}catch(e){}" style="width:30px;display:inline;" ><a href="'.$userProfile.$answerUserDisplayName.'">'.$answerUserDisplayName.'\'s</a></span>&nbsp;<span title="click to change your display name" style="cursor:pointer;" onClick="showUpdateUserNameImage(\'Edit your display name here\',\'\',\'\',\'\',0);"><img src="/public/images/fU.png" /></span>';
    }else{
          $answerUserDisplayTextForHeading = '<span onmouseover="javascript:try{showUserCommonOverlayForCard(this,'.$answerUserId.');}catch(e){}" style="width:30px;display:inline;" ><a href="'.$userProfile.$answerUserDisplayName.'">'.$answerUserDisplayName.'\'s</a></span>';
    }*/
    $answerUserDisplayTextForHeading = '<a href="'.$userProfile.$answerUserDisplayName.'">'.$answerUserDisplayName.'\'s</a>';
    $answerUserDisplayText = getDisplayNameText($userId, $answerUserId, $answerUserDisplayName, $answerVCardStatus);
    $answerUserLevelText = '<span class=\'forA \'><a href="/shikshaHelp/ShikshaHelp/upsInfo">'.$answerUserLevel.'</a></span>';
    $raterUserDisplayText = getDisplayNameText($userId, $raterUserId, $raterUserDisplayName, $raterVCardStatus);
    $raterUserLevelText = '<span class=\'forA \'><a href="/shikshaHelp/ShikshaHelp/upsInfo">'.$raterUserLevel.'</a></span>';
    $commenterUserDisplayText = getDisplayNameText($userId, $commenterUserId, $commenterUserDisplayName, $commenterVCardStatus);
    $commenterUserLevelText = '<span class=\'forA \'><a href="/shikshaHelp/ShikshaHelp/upsInfo">'.$commenterUserLevel.'</a></span>';
    $catCountLink = '/messageBoard/MsgBoard/discussionHome/'.$CategoryCountryArray[$questionMsgId]['categoryId'].'/0/'.$CategoryCountryArray[$questionMsgId]['countryId'];
    $catCountText = $CategoryCountryArray[$questionMsgId]['category'].' - '.$CategoryCountryArray[$questionMsgId]['country'];
    $userImageURL = ($userImageURL=='')?'/public/images/photoNotAvailable.gif':$userImageURL;

    $displayListing = false;
    $listingTitle='';
    $heightOfText = 'height:28px;overflow:hidden;';
    $heightOfTitle = 'height:26px;overflow:hidden;line-height:15px';
?>
<!-- Main Div Start -->
<div class="aqAns" style="position:absolute;height:60px; width:480px; padding-top:5px;padding-bottom:5px;left:0px;top:<?php echo ( (($showCount-$displayEntries)*(-1)) * 72);?>px;<?php if($showCount>$displayEntries) echo 'opacity:0; display:none;';?>" id="wish<?php echo $showCount?>">

    <!--<div class="lineSpace_10">&nbsp;</div>-->
    <!-- Start Question section -->
    <div class="wdh100">
        <!-- Block Start to display the User image -->
        <div class="buzzFigure">
            <?php if($userId == $userImageURLOwnerId){ ?>
              <img id="<?php echo 'userProfileImageForAnswer'.$questionList[$i][0]['msgId'];?>" src="<?php echo getTinyImage($userImageURL);?>" />
            <?php }else{ ?>
              <img src="<?php echo getTinyImage($userImageURL);?>"/>
            <?php } ?>
        </div>
        <!-- Block End to display the User image -->
        <div class="buzzContent">
                <!-- Start of Question Headline Block -->
                <?php
                    switch($questionList[$i]['type']){
                        case 'question':
                              echo "<div style='padding-bottom:4px;".$heightOfTitle."'><b>".$questionUserDisplayText."</b>&nbsp;".$questionUserLevelText." <b>asked</b><br /> a question <span class='grayFont'>".makeRelativeTime($questionList[$i][0]['creationDate'])."</span></div>";
                              $questionText = html_entity_decode(html_entity_decode($questionText,ENT_NOQUOTES,'UTF-8'));
                              $questionText = substr($questionText, 0, 197);
                              echo "<div ".$questionFontClass." style='".$heightOfText."'>".$questionText."</div></a>"; 
                              break;
                        case 'discussion':
                              echo "<div style='padding-bottom:4px;".$heightOfTitle."'><b>".$questionUserDisplayText."</b>&nbsp;".$questionUserLevelText." <b>started</b><br /> a discussion on <a href='".$questionList[$i][0]['url']."' $questionFontStyle>".formatDiscussionforList($questionList[$i][0]['msgTxt'],300)."</a> <span class='grayFont'>".makeRelativeTime($questionList[$i][0]['creationDate'])."</span></div>";
                              $discussionText = html_entity_decode(html_entity_decode($questionList[$i][0]['description'],ENT_NOQUOTES,'UTF-8'));
                              $discussionText = formatQNAforQuestionDetailPage(substr($discussionText, 0, 197),300);
                              echo "<div ".$questionFontClass." style='".$heightOfText."'>".$discussionText."</div></a>";
                              break;
                        case 'discussioncomment':
                              if($questionUserId==$commenterUserId) $displayNameString = "their"; else $displayNameString = $questionUserDisplayTextForHeading;
                              echo "<div style='padding-bottom:4px;".$heightOfTitle."'><b>".$commenterUserDisplayText."</b>&nbsp;".$commenterUserLevelText." <b>commented</b><br /> on ".$displayNameString." discussion post &nbsp;<span class='grayFont'>".makeRelativeTime($questionList[$i][$userCommentTimeDisplayNumber]['creationDate'])."</span></div>";
                              $discussionCText = html_entity_decode(html_entity_decode($questionList[$i][$userCommentTimeDisplayNumber]['msgTxt'],ENT_NOQUOTES,'UTF-8'));
                              $discussionCText = formatQNAforQuestionDetailPage(substr($discussionCText, 0, 197),300);
                              echo "<div ".$questionFontClass."  style='".$heightOfText."'>".$discussionCText."</div></a>";
                              break;
                        case 'announcement':
                              //echo "<div style='padding-bottom:6px;'><b>".$questionUserDisplayText."</b>&nbsp;".$questionUserLevelText." <b>posted</b> ".$questionList[$i][0]['msgTxt']."&nbsp;<span class='Fnt11 grayFont'>".$questionList[$i][0]['creationDate']."</span></div>";
                              echo "<div style='padding-bottom:4px;".$heightOfTitle."'><b>".$questionUserDisplayText."</b>&nbsp;".$questionUserLevelText." <b>posted</b><br /> <a href='".$questionList[$i][0]['url']."' $questionFontStyle>".$questionList[$i][0]['msgTxt']."</a> <span class='grayFont'>".makeRelativeTime($questionList[$i][0]['creationDate'])."</span></div>";
                              $discussionText = html_entity_decode(html_entity_decode($questionList[$i][0]['description'],ENT_NOQUOTES,'UTF-8'));
                              $discussionText = substr($discussionText, 0, 197);
                              echo "<div ".$questionFontClass." style='".$heightOfText."'>".$discussionText."</div></a>";
                              break;
                        case 'announcementcomment':
                              if($questionUserId==$commenterUserId) $displayNameString = "their"; else $displayNameString = $questionUserDisplayTextForHeading;
                              $time = (count($questionList[$i])>4)?makeRelativeTime($questionList[$i][$userCommentTimeDisplayNumber]['creationDate']):$questionList[$i][$userCommentTimeDisplayNumber]['creationDate'];
                              echo "<div style='padding-bottom:4px;".$heightOfTitle."'><b>".$commenterUserDisplayText."</b>&nbsp;".$commenterUserLevelText." <b>commented</b><br /> on ".$displayNameString." post &nbsp;<span class='grayFont'>".$time."</span></div>";
                              $discussionCText = html_entity_decode(html_entity_decode($questionList[$i][$userCommentTimeDisplayNumber]['msgTxt'],ENT_NOQUOTES,'UTF-8'));
                              $discussionCText = substr($discussionCText, 0, 197);
                              echo "<div ".$questionFontClass." style='".$heightOfText."'>".$discussionCText."</div></a>";
                              break;
                        case 'answer':
                              echo "<div style='padding-bottom:4px;".$heightOfTitle."'><b>".$answerUserDisplayText."</b>&nbsp;".$answerUserLevelText." <b>answered</b><br /> ".$questionUserDisplayTextForHeading." question <span class='grayFont'>".makeRelativeTime($questionList[$i][1]['creationDate'])."</span></div>";
                              $answerText = html_entity_decode(html_entity_decode($answerText,ENT_NOQUOTES,'UTF-8'));
                              $answerText = formatQNAforQuestionDetailPage(substr($answerText,0,197),300); 
                              echo "<div ".$answerFontClass." style='".$heightOfText."'>".$answerText."</div>";
                              break;
                        case 'rating':
                              $showOtherRating = '';
                              if($questionList[$i][1]['digUp']==2) $showOtherRating = " and <b>".($questionList[$i][1]['digUp']-1)." other</b>";
                              else if($questionList[$i][1]['digUp']>2) $showOtherRating = " and <b>".($questionList[$i][1]['digUp']-1)." others</b>";
                              echo "<div style='padding-bottom:4px;".$heightOfTitle."'><b>".$raterUserDisplayText."</b>&nbsp;".$raterUserLevelText.$showOtherRating." <b>gave</b> thumbs up <img src='/public/images/hUp.gif'/> to ".$answerUserDisplayTextForHeading." answer <span class='grayFont'>".makeRelativeTime($questionList[$i]['sortingTime'])."</span></div>";
                              $answerText = html_entity_decode(html_entity_decode($answerText,ENT_NOQUOTES,'UTF-8'));
                              $answerText = formatQNAforQuestionDetailPage(substr($answerText,0,197),300); 
                              echo "<div ".$answerFontClass." style='".$heightOfText."'>".$answerText."</div>";
                              break;
                        case 'bestanswer':
                              echo "<div style='padding-bottom:4px; ".$heightOfTitle." overflow:hidden;'><b>".$answerUserDisplayText."</b>&nbsp;".$answerUserLevelText." your answer is selected<br /> as <b>\"The Best Answer\"</b> by ".$questionUserDisplayText."</div>";
                              $answerText = html_entity_decode(html_entity_decode($answerText,ENT_NOQUOTES,'UTF-8'));
                              $answerText = formatQNAforQuestionDetailPage(substr($answerText,0,197),300); 
                              echo "<div ".$answerFontClass." style='".$heightOfText."'>".$answerText."</div>";
                              break;
                        case 'comment':
                              if($commenterUserId==$answerUserId) $displayNameString = "their"; else $displayNameString = $answerUserDisplayTextForHeading;
                              echo "<div style='padding-bottom:4px;".$heightOfTitle."'><b>".$commenterUserDisplayText."</b>&nbsp;".$commenterUserLevelText." <b>commented</b><br /> on ".$displayNameString." answer <span class='grayFont'>".makeRelativeTime($questionList[$i][$commentNumberToDisplay]['creationDate'])."</span></div>";
                              $discussionCText = html_entity_decode(html_entity_decode($questionList[$i][$userCommentTimeDisplayNumber]['msgTxt'],ENT_NOQUOTES,'UTF-8'));
                              $discussionCText = formatQNAforQuestionDetailPage(substr($discussionCText, 0, 197),300);
                              echo "<div ".$questionFontClass." style='".$heightOfText."'>".$discussionCText."</div></a>";
                              break;
                    }
                ?>
                <!-- End of Question Headline Block -->


                <input type="hidden" id="start<?php echo $questionMsgId;?>" value=0 />
                <input type="hidden" id="count<?php echo $questionMsgId;?>" value=2 />
            </div>
        <div class="clear_B"></div>
    </div>
    <!-- End Question section -->
    <div class="lineSpace_3 defaultAdd">&nbsp;</div>
    <div class="dottedLine defaultAdd" style="height:3px;">&nbsp;</div>
    <div class="lineSpace_2 defaultAdd">&nbsp;</div>
</div>
<!-- Main Div End -->
<?php
  echo "<script>"; 
  echo "var threadId = '".$questionList[$i][0]['threadId']."'; \n";		
  echo "</script>";
}	//End of If for Level type
$lastTimeStamp = $questionList[$i]['sortingTime'];
}
?>
<input type="hidden" id="pageKeyForSubmitComment" value="<?php echo $pageKeySuffixForDetail.'SUBMITCOMMENT'; ?>" />
<input type="hidden" id="pageKeyForReportAbuse" value="<?php echo $pageKeySuffixForDetail.'REPORTABUSE'; ?>" />
<input type="hidden" id="pageKeyForDigVal" value="<?php echo $pageKeySuffixForDetail.'UPDATEDIGVAL'; ?>" />

</div>
<!-- Container Div End -->
</div>
<!-- Top Div End -->

<div class="lineSpace_5"></div>
<div><b><a href='<?php echo $homeUrl;?>'>View all activities &#187</a></b></div>

<script>
//Set the top field of Divs
var startDiv = 2;
var middleDiv1 = 1;
var middleDiv2 = 1;
var endDiv = 1;
var totalDivs = <?php echo count($questionList);?>;
var cafeItemHeight = 70;
setTimeout("rotateDivs()", 5000);
</script>
</div> 
<?php
} 
?>
