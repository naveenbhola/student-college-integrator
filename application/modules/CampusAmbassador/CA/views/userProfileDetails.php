<!--Start_Shiksha_MainNavigation--> 
<?php
$showExpertLink = true;
function getLevelData($level)
{
	  $dataArray = array();
	  $level = trim($level);
	  $html = "";
	  $bgColor = "#FFFFFF";
	  switch($level){
		case 'Advisor': //$html = "<span title='Grey Star denotes an ?Advisor? on our Panel of Experts (points 1000 to 2500)'><img src='/public/images/str_1l.gif' align='absmiddle' border='0'></span>"; 
						$html = 'str_1lA';
						$bgColor = "#CCCCCC";
						$progressionClass = "a_1m";
						break;
		case 'Senior Advisor': //$html = "<span title='Blue Star denotes a ?Senior Advisor? on our Panel of Experts (points 2500 to 5000)'><img src='/public/images/str_2l.gif' align='absmiddle' border='0'></span>";
						$html = 'str_2lA';
						$bgColor = "#F7FEFF";
						$progressionClass = "s_1m";
						break;
		case 'Lead Advisor': //$html = "<span title='Pink Star denotes a ?Lead Advisor? on our Panel of Experts (points 5000	to 10000)'><img src='/public/images/str_3l.gif' align='absmiddle' border='0'></span>";
						$html = 'str_3lA';
						$bgColor = "#FF82AB";
						$progressionClass = "l_1m";
						break;
		case 'Principal Advisor': //$html = "<span title='Green Star denotes a ?Principal Advisor? on our Panel of Experts (points 10,000 to 20,000)'><img src='/public/images/str_4l.gif' align='absmiddle' border='0'></span>";
						$html = 'str_4lA';
						$bgColor = "#99FF66";
						$progressionClass = "p_1m";
						break;
		case 'Chief Advisor': //$html = "<span title='Orange Star denotes a ?Chief Advisor? on our Panel of Experts (points 20,000 +)'><img src='/public/images/str_5l.gif' align='absmiddle' border='0'></span>";
						$html = 'str_5lA';
						$bgColor = "#FFC125";
						$progressionClass = "c_1m";
						break;
		case 'Trainee': $progressionClass = "t_1m";
						break;
		case 'Beginner': $progressionClass = "b_1m";
						break;
	  }
	  $dataArray = array('starHTML'=>$html,'bgColor'=>$bgColor,'progressionClass'=>$progressionClass);
	  return $dataArray;
}

if(isset($userDetailsArray) && is_array($userDetailsArray) && is_array($otherUserDetails)){
  $levelData = getLevelData($userDetailsArray[0][0][0]['ownerLevel']);
  $userId = isset($validateuser[0]['userid'])?$validateuser[0]['userid']:0;
  $expertString = '';
  if($userDetailsArray[0][0][0]['ownerLevel']!='Beginner' && $userDetailsArray[0][0][0]['ownerLevel']!='Trainee'){
  if(is_array($userExpertize) && count($userExpertize)>1){
	$expertString = $userExpertize[0]['name']." , ".$userExpertize[1]['name']." Expert";
  }
  else if(is_array($userExpertize) && count($userExpertize)>0){
	$expertString = $userExpertize[0]['name']." Expert";
  }
  }
  if($userDetailsArray[0][0][0]['userName']==' ') 
	$displayUserName = $userDetailsArray[0][0][0]['displayname'];
  else 
	$displayUserName = $userDetailsArray[0][0][0]['userName'];

  $isFollowing = false;
  if(isset($followUser) && is_array($followUser)){
	for($i=0;$i<count($followUser);$i++)
	  if($userId == $followUser[$i]['followingUserId'])
		  $isFollowing = true;
  }
?>


<div class="lineSpace_10">&nbsp;</div>
<div class="">

	<div class="float_L w117">
	  <img src="<?php if($campusambassador[0]['ca']['imageURL']!='') echo getMediumImage($campusambassador[0]['ca']['imageURL']);else echo getMediumImage('/public/images/photoNotAvailable.gif');?>" border='0'/>
	  <?php if(isset($campusambassador[0]['ca']['aboutMe']) && ($userId==$viewedUserId)){ ?><div class="mb5 Fnt11" style="margin-top:5px;text-align:center;"><a href="/CA/CampusAmbassador/getCAProfileForm">Edit photo</a></div><?php } ?>	  
	</div>
	<div class="float_L w395">
		<div class="plr10">
			<div>
			      <b class="Fnt16"><?php if($userDetailsArray[0][0][0]['userName']==' ') {echo $userDetailsArray[0][0][0]['displayname'];} else echo $userDetailsArray[0][0][0]['userName'];?></b>
			      ( <?php echo ($userDetailsArray[0][0][0]['displayname']);?> )
			      <!--<img src='/public/images/current-stnt-patch.gif' border=0 align='absbottom'/>-->
			      <span class="ca-badges"><?php echo $badgeInsCourseName['badge'];?></span>
		        </div>
			<div class="spacer5 clearFix"></div>
			<div class="Fnt13 mb5">
			      <div><span class="bld">Course Name: </span><span><?php echo $badgeInsCourseName['courseName'];?></span></div>
				<div class="spacer5 clearFix"></div>
			      <div><span class="bld">Institute Name: </span><span><?php echo $badgeInsCourseName['insName'];?></span></div>
				<div class="spacer5 clearFix"></div>
			      <?php /*if($userId!=$viewedUserId && is_array($followUser) && count($followUser)>1){ ?>
				  <span class="Fnt11"> (<a href="javascript:void(0);" onClick="showFollowingPersons('<?php echo $userDetailsArray[0][0][0]['userId'];?>','<?php echo $userDetailsArray[0][0][0]['displayname'];?>'); return false;"><?php echo count($followUser);?> followers</a>)</span>
			      <?php }else if($userId!=$viewedUserId && is_array($followUser) && count($followUser)==1){ ?>
				  <span class="Fnt11"> (<a href="javascript:void(0);" onClick="showFollowingPersons('<?php echo $userDetailsArray[0][0][0]['userId'];?>','<?php echo $userDetailsArray[0][0][0]['displayname'];?>'); return false;"><?php echo count($followUser);?> follower</a>)</span>
			      <?php }*/ ?>
			</div>

			<div class="pntDef  fs12" style="background-color:#fcfded; border:1px solid #f5efd9">
				<div class="ana_a" style="margin-bottom:5px;height:50px;">
				    <b><?php echo (ceil(($userDetailsArray[0][0][0]['bestAnswers']/$userDetailsArray[0][0][0]['totalAnswers'])*100))."%";?></b> Best Answers, <b><?php echo ($otherUserDetails[0]['likes']=='')?0:$otherUserDetails[0]['likes'];?></b> <img src="/public/images/hUp.gif"> likes<br />
				    <b><?php echo ($userDetailsArray[0][0][0]['totalAnswers']);?></b> <?php echo ($userDetailsArray[0][0][0]['totalAnswers']>1)?"answers":"answer";?><br />
				    <b><?php echo ($otherUserDetails[0]['totalPoints']=='')?0:$otherUserDetails[0]['totalPoints'];?></b> Points (<b><?php echo ($otherUserDetails[0]['weeklyPoints']=='')?0:$otherUserDetails[0]['weeklyPoints'];?></b> this week)<br />
				
                                </div>
				<div class="ln" style="margin-top:0px">&nbsp;</div>
				<?php if(isset($participateUserDetails)){ ?>
				    <?php if($participateUserDetails[0]['discussionPosts']!='' && $participateUserDetails[0]['discussionPosts']!='0'){ ?>
					<div class="ana_blog " style="margin-bottom:5px;"><b><?php echo $participateUserDetails[0]['discussionPosts'];?></b> discussion <?php echo ($participateUserDetails[0]['discussionPosts']>1)?'posts':'post';?><br/></div>
				    <?php } ?>
				    <?php if($participateUserDetails[0]['announcementPosts']!='' && $participateUserDetails[0]['announcementPosts']!='0'){ ?>
					<div class="ana_mike " style="margin-bottom:5px;"><b><?php echo $participateUserDetails[0]['announcementPosts'];?></b> <?php echo ($participateUserDetails[0]['announcementPosts']>1)?'announcements':'announcement';?><br/></div>
				    <?php } ?>
				    <div><b><?php echo ($participateUserDetails[0]['totalParticipatePoints']=='')?0:$participateUserDetails[0]['totalParticipatePoints'];?></b> Cafe points (<b><?php echo ($participateUserDetails[0]['weeklyParticipatePoints']=='')?0:$participateUserDetails[0]['weeklyParticipatePoints'];?></b> this week)</div>
				<?php } ?>
			</div>
			<?php if(isset($campusambassador[0]['ca']['aboutMe']) && $campusambassador[0]['ca']['aboutMe']!=''){ ?><div class="mb5"><b>About me</b>: <span class="Fnt11"><?php echo $campusambassador[0]['ca']['aboutMe']; ?></span></div><?php } ?>
			<?php if(isset($campusambassador[0]['ca']['aboutMe']) && ($userId==$viewedUserId)){?><div class="mb5 Fnt11" style="margin-top:5px;"><a href="/CA/CampusAmbassador/getCAProfileForm" >Edit Profile</a></div><?php } ?>
		</div>
	</div>
	<!--<div class="float_L w220 brdright">-->
	  <div class="float_L" style="width:450px;">
<!-- 		<div class="lineSpace_15">&nbsp;</div> -->
		<div class="pl10">
			Member Since: <?php echo ($otherUserDetails[0]['creationDate']);?><br />
			<div class="lineSpace_10">&nbsp;</div>
			<?php if($userId!=$viewedUserId)
			  { 
			 ?>
			<div class="lineSpace_17">
				<?php if($isFollowing == false){ ?><span id="followUserLink<?php echo $viewedUserId;?>"><span class="follow-button" onclick="followUser('<?php echo $viewedUserId;?>','ASK_PROFILE_MIDDLEPANEL_ADDUSER',this,'','','<?php echo $followTrackingPageKeyId;?>');">Follow</span></span>
				&nbsp;&nbsp;<span id="followUserImage" style="display:none;"><img src='/public/images/working.gif' align='absmiddle'/></span><br />
				<?php }else{ ?><span class="Fnt11" style="background:url('/public/images/following.gif') no-repeat scroll left top transparent; padding-left:27px;">Following</span><br /><?php } ?> 

				<div class="lineSpace_8"></div>
				<!--<div style="padding-left:5px;"><span style="background:url('/public/images/inbox_icon.gif') no-repeat scroll left top transparent; padding-left:23px;cursor:pointer;" class="Fnt11" onclick="javascript: try{ showMailOverlay('<?php echo $userId;?>','','<?php echo $viewedUserId;?>','<?php echo $userDetailsArray[0][0][0]['displayname'];?>','ASK_PROFILE_MIDDLEPANEL_MAILUSER',this) } catch (e) {} ">Email</span></div><br />-->
			</div>

			<?php 
			  }
			  else{ //In case of logged in user, we will show the people in his network and people following him
			  $showMargin = false;	//Check if the 15 pixel margin needs to be added
			  if(isset($followUser) && is_array($followUser) && count($followUser)==1){ $showMargin = true;
			?>
				<span class="Fnt11"><a href="javascript:void(0);" class="grnArw" onclick="javascript:try { showFollowingPersons('<?php echo $viewedUserId;?>'); } catch(e) {}">1 person</a> is following you</span>
			  <?php }else if(isset($followUser) && is_array($followUser) && count($followUser)>1){ $showMargin = true; ?>
				<span class="Fnt11"><a href="javascript:void(0);" class=" grnArw" onclick="javascript:try { showFollowingPersons('<?php echo $viewedUserId;?>'); } catch(e) {}"><?php echo count($followUser);?> people</a> are following you</span>
			  <?php } ?>
			<?php if($showMargin){ ?><div class="lineSpace_10">&nbsp;</div><?php } ?>
			<?php } ?>

						<!--Widget for panelExpert-->
			 <?php /*if($userId!=$viewedUserId){ ?>
			<div class="expert-btn" style="margin-top:20px;font-size:17px;padding:8px 10px 0 10px;float:left;">
				<a href="/CA/CampusAmbassador/getCAProfileForm" style="color:#713712;"><span>Become a Campus<br/>Representative</a></strong></span>
			</div>
			<div class="lineSpace_10">&nbsp;</div>
			  <?php }else{ */?>
			  <?php if($userId==$viewedUserId){ ?>
			<div class="expert-btn" style="margin-top:50px;font-size:12px;padding:8px 10px 0 10px;float:left">
				<a href="/CA/CampusAmbassador/getCAProfileForm" style="color:#713712;"><span>You are Campus Representative<br /><strong>Edit your profile</a></strong></span>
			</div>
			<div class="lineSpace_10">&nbsp;</div>
			<?php } ?>
			<!--End_Widget for panelExpert-->
			<?php //if(isset($userDetailsArray[0][0][0]['blogURL'])){ 
			if($campusambassador[0]['ca']['blogURL']!='' || $campusambassador[0]['ca']['twitterURL']!='' || $campusambassador[0]['ca']['youtubeURL']!='' || $campusambassador[0]['ca']['facebookURL']!=''){
			?>
			<div style="margin:5px 0 0">
				<div class="bld mb10"><?php if($userId!=$viewedUserId) echo "Catch me on:"; else echo "People will catch you on:";?></div>
				<div class="mb5">
					<?php if(isset($campusambassador[0]['ca']['blogURL']) && $campusambassador[0]['ca']['blogURL']!=''){ ?><a target="_blank" href="<?php echo makeProperLink($campusambassador[0]['ca']['blogURL']);?>"><img src="/public/images/blog.gif" hspace="5" border="0" title="<?php echo $campusambassador[0]['ca']['blogURL'];?>"/></a><div class="lineSpace_6"></div><?php } ?>
					<?php if(isset($campusambassador[0]['ca']['facebookURL']) && $campusambassador[0]['ca']['facebookURL']!=''){ ?><a target="_blank" href="<?php echo makeProperLink($campusambassador[0]['ca']['facebookURL']);?>"><img src="/public/images/facebookwhite.gif" hspace="5" border="0" title="<?php echo $campusambassador[0]['ca']['facebookURL'];?>"/></a><div class="lineSpace_6"></div><?php } ?>
					<?php if(isset($campusambassador[0]['ca']['linkedInURL']) && $campusambassador[0]['ca']['linkedInURL']!=''){ ?><a target="_blank" href="<?php echo makeProperLink($campusambassador[0]['ca']['linkedInURL']);?>"><img src="/public/images/linkedIn.gif" hspace="5" border="0" title="<?php echo $campusambassador[0]['ca']['linkedInURL'];?>"/></a><div class="lineSpace_6"></div><?php } ?>
					<?php if(isset($campusambassador[0]['ca']['twitterURL']) && $campusambassador[0]['ca']['twitterURL']!=''){ ?><a target="_blank" href="<?php echo makeProperLink($campusambassador[0]['ca']['twitterURL']);?>"><img src="/public/images/twitter.gif" hspace="5" border="0" title="<?php echo $campusambassador[0]['ca']['twitterURL'];?>"/></a><div class="lineSpace_6"></div><?php } ?>
					<?php if(isset($campusambassador[0]['ca']['youtubeURL']) && $campusambassador[0]['ca']['youtubeURL']!=''){ ?><a target="_blank" href="<?php echo makeProperLink($campusambassador[0]['ca']['youtubeURL']);?>"><img src="/public/images/youtube.gif" hspace="5" border="0" title="<?php echo $campusambassador[0]['ca']['youtubeURL'];?>"/></a><div class="lineSpace_6"></div><?php } ?>
				</div>
			</div>
			<?php if(($userId==$viewedUserId)){ ?><div class="mb5 Fnt11" style="margin-top:5px;"><a href="/CA/CampusAmbassador/getCAProfileForm">Edit details</a></div><?php } ?>
			<?php //}
			} ?>
		</div>

		<div class="lineSpace_25">&nbsp;</div>
	</div>
	<div class="float_L w230">            	
		<div class="pl10" style="position:relative">
		<!--	<div class="lineSpace_10">&nbsp;</div>
			<div id="pointChart">

				<div class="<?php //echo $levelData['progressionClass'];?>">
					<div class="chtDiv b_1">&nbsp;</div>
					<div class="chtDiv t_1">&nbsp;</div>
					<div class="chtDiv a_1">&nbsp;</div>
					<div class="chtDiv s_1">&nbsp;</div>
					<div class="chtDiv l_1">&nbsp;</div>
					<div class="chtDiv p_1">&nbsp;</div>
					<div class="chtDiv c_1">&nbsp;</div>                        
				</div>

				<div class="clear_B">&nbsp;</div>
			</div>-->
			<div class="Fnt11 lineSpace_15" style="position:absolute;top:0">
				<!--Member Since: <?php //echo ($otherUserDetails[0]['creationDate']);?><br />-->
				<?php /*if($otherUserDetails[0]['upgradeDate']!=''){ ?>Last Promoted: <?php echo ($otherUserDetails[0]['upgradeDate']);?><?php }*/ ?>
			</div>
		</div>
		<!--<div style="padding-left:27px;color:#666"><b>Expertise Progression</b></div>-->


		
	</div>
	<div class="clear_B">&nbsp;</div>
</div>
<?php
} 

function makeProperLink($url){
        if( strpos ($url , 'https') === 0 ){
                return $url;
        }
        else{
                return 'https://'.$url;
        }
}

?>
