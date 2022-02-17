<!--Start_Shiksha_MainNavigation--> 
<?php
$showExpertLink = true;
if(isset($userDetailsArray) && is_array($userDetailsArray) && is_array($otherUserDetails)){
  //$levelData = getLevelData($userDetailsArray[0][0][0]['ownerLevel']);
  $userId = isset($validateuser[0]['userid'])?$validateuser[0]['userid']:0;
  $expertString = '';
  $isExpert = stripos($userDetailsArray[0][0][0]['ownerLevel'],'expert');
  if($isExpert !== false){
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
	  <img src="<?php if($userDetailsArray[0][0][0]['imageURL']!='') echo getMediumImage($userDetailsArray[0][0][0]['imageURL']);else echo getMediumImage('/public/images/photoNotAvailable.gif');?>" border='0'/>
	  <?php if(isset($userDetailsArray[0][0][0]['aboutMe']) && ($userId==$viewedUserId)){ ?><div class="mb5 Fnt11" style="margin-top:5px;text-align:center;"><a href="/messageBoard/MsgBoard/expertOnboard">Edit photo</a></div><?php } ?>	  
	</div>
	<div class="float_L w395">
		<div class="plr10">
			<div>
			      <b class="Fnt16"><?php echo $displayUserName;?></b>
			      <?php if($userId == 11 || $userId == 670062) {?>
			      ( <?php echo ($userDetailsArray[0][0][0]['displayname']);?> )
			      <?php } ?>
			      <?php if($isCampusAmbassador){ ?><img src='/public/images/current-stnt-patch.gif' border=0 align='absbottom'/>
			      <?php }?>
		        </div>
			<div class="Fnt13 mb5">
			      <span class="bld"><?php echo ($userDetailsArray[0][0][0]['ownerLevel']);?> - Q&amp;A</span>
			      <?php if($userId!=$viewedUserId && is_array($followUser) && count($followUser)>1){ ?>
				  <span class="Fnt11"> (<a href="javascript:void(0);" onClick="showFollowingPersons('<?php echo $userDetailsArray[0][0][0]['userId'];?>','<?php echo $userDetailsArray[0][0][0]['firstname']; echo ' '; if($userDetailsArray[0][0][0]['lastname']!= '') echo $userDetailsArray[0][0][0]['lastname'];?>'); return false;"><?php echo count($followUser);?> followers</a>)</span>
			      <?php }else if($userId!=$viewedUserId && is_array($followUser) && count($followUser)==1){ ?>
				  <span class="Fnt11"> (<a href="javascript:void(0);" onClick="showFollowingPersons('<?php echo $userDetailsArray[0][0][0]['userId'];?>','<?php echo $userDetailsArray[0][0][0]['firstname']; echo ' '; if($userDetailsArray[0][0][0]['lastname'] !='') echo $userDetailsArray[0][0][0]['lastname'];?>'); return false;"><?php echo count($followUser);?> follower</a>)</span>
			      <?php } ?>
			</div>
			<?php if($expertString!=''){ ?><div class=""><?php echo $expertString;?></div><?php } ?>

			<div class="pntDef fs12" style="background-position: 282px 13px">
				<div class="ana_a" style="margin-bottom:5px;height:50px;">
				    <b><?php echo ($otherUserDetails[0]['likes']=='')?0:$otherUserDetails[0]['likes'];?></b> <img src="/public/images/hUp.gif"> upvotes                                  
                                    <br/>
				    <b><?php echo ($userDetailsArray[0][0][0]['totalAnswers']);?></b> <?php echo ($userDetailsArray[0][0][0]['totalAnswers']>1)?"answers":"answer";?><br />
				    <b><?php echo ($otherUserDetails[0]['totalPoints']=='')?0:$otherUserDetails[0]['totalPoints'];?></b> Points (<b><?php echo ($otherUserDetails[0]['weeklyPoints']=='')?0:$otherUserDetails[0]['weeklyPoints'];?></b> this week)
				
                                </div>
 			        <div class="ln" style="margin-top:0px">&nbsp;</div>	
				<?php if(isset($participateUserDetails)){ ?>
				    <?php if($participateUserDetails[0]['discussionPosts']!='' && $participateUserDetails[0]['discussionPosts']!='0'){ ?>
					<div class="ana_blog " style="margin-bottom:5px;"><b><?php echo $participateUserDetails[0]['discussionPosts'];?></b> discussion <?php echo ($participateUserDetails[0]['discussionPosts']>1)?'posts':'post';?><br/></div>
				    <?php } ?>
				    <?php if($participateUserDetails[0]['announcementPosts']!='' && $participateUserDetails[0]['announcementPosts']!='0'){ ?>
					<div class="ana_mike " style="margin-bottom:5px;"><b><?php echo $participateUserDetails[0]['announcementPosts'];?></b> <?php echo ($participateUserDetails[0]['announcementPosts']>1)?'announcements':'announcement';?><br/></div>
				    <?php } ?>
				<?php } ?>
			</div>
			<?php if(isset($userDetailsArray[0][0][0]['highestQualification']) && $userDetailsArray[0][0][0]['highestQualification']!=''){ ?><div class="mb5"><b>Education</b>: <?php echo ($userDetailsArray[0][0][0]['highestQualification']);?></div><?php } ?>
			<?php if(isset($userDetailsArray[0][0][0]['designation']) && $userDetailsArray[0][0][0]['designation']!=''){ ?><div class="mb5"><b>Current Position</b>: <?php echo $userDetailsArray[0][0][0]['designation'];?></div><?php } ?>
			<?php if(isset($userDetailsArray[0][0][0]['aboutMe']) && $userDetailsArray[0][0][0]['aboutMe']!=''){ ?><div class="mb5"><b>About me</b>: <span class="Fnt11"><?php echo $userDetailsArray[0][0][0]['aboutMe']; ?></span></div><?php } ?>
			<?php if(isset($userDetailsArray[0][0][0]['aboutCompany']) && $userDetailsArray[0][0][0]['aboutCompany']!=''){ ?><div><b>About Company</b>: <span class="Fnt11"><?php echo $userDetailsArray[0][0][0]['aboutCompany']; ?></span></div><?php } ?>
			<?php if(isset($userDetailsArray[0][0][0]['aboutMe']) && ($userId==$viewedUserId)){ $showExpertLink = false; ?><div class="mb5 Fnt11" style="margin-top:5px;"><a href="/messageBoard/MsgBoard/expertOnboard">Edit details</a></div><?php } ?>
			
		</div>
	</div>
	<div class="float_L w220">
<!-- 		<div class="lineSpace_15">&nbsp;</div> -->
		<div class="pl10">
			<?php if($userId!=$viewedUserId)
			  { 
			 ?>
			<div class="lineSpace_17">
				<?php if($isFollowing == false){ ?><span id="followUserLink<?php echo $viewedUserId;?>"><span class="Fnt11" style="cursor:pointer;background:url('/public/images/follow.gif') no-repeat scroll left top transparent; padding-left:27px;" onclick="followUser('<?php echo $viewedUserId;?>','ASK_PROFILE_MIDDLEPANEL_ADDUSER',this,'','','<?php echo $followTrackingPageKeyId;?>');">Follow</span></span>
				&nbsp;&nbsp;<span id="followUserImage" style="display:none;"><img src='/public/images/working.gif' align='absmiddle'/></span><br />
				<?php }else{ ?><span class="Fnt11" style="background:url('/public/images/following.gif') no-repeat scroll left top transparent; padding-left:27px;">Following</span><br /><?php } ?> 

				<div class="lineSpace_8"></div>
				<div style="padding-left:5px;">
					<span style="background:url('/public/images/inbox_icon.gif') no-repeat scroll left top transparent; padding-left:23px;cursor:pointer;" class="Fnt11" onclick="javascript: try{ showMailOverlay('<?php echo $userId;?>','','<?php echo $viewedUserId;?>','<?php echo $userDetailsArray[0][0][0]['firstname']; echo ' '; if($userDetailsArray[0][0][0]['lastname']) echo $userDetailsArray[0][0][0]['lastname'];?>','ASK_PROFILE_MIDDLEPANEL_MAILUSER',this,'<?php echo $emailTrackingPageKeyId;?>') } catch (e) {} ">Email</span>
				</div>
				<br />
			</div>
			<div class="lineSpace_10">&nbsp;</div>
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

			<?php if(isset($userDetailsArray[0][0][0]['blogURL'])){ 
			if($userDetailsArray[0][0][0]['blogURL']!='' || $userDetailsArray[0][0][0]['facebookURL']!='' || $userDetailsArray[0][0][0]['linkedInURL']!='' || $userDetailsArray[0][0][0]['twitterURL']!='' || $userDetailsArray[0][0][0]['youtubeURL']!=''){
			?>
			<div style="margin:5px 0 0">
				<div class="bld mb10"><?php if($userId!=$viewedUserId) echo "Catch me on:"; else echo "People will catch you on:";?></div>
				<div class="mb5">
					<?php if(isset($userDetailsArray[0][0][0]['blogURL']) && $userDetailsArray[0][0][0]['blogURL']!=''){ ?><a href="<?php echo $userDetailsArray[0][0][0]['blogURL'];?>"><img src="/public/images/blog.gif" hspace="5" border="0" title="<?php echo $userDetailsArray[0][0][0]['blogURL'];?>"/></a><div class="lineSpace_6"></div><?php } ?>
					<?php if(isset($userDetailsArray[0][0][0]['facebookURL']) && $userDetailsArray[0][0][0]['facebookURL']!=''){ ?><a href="<?php echo $userDetailsArray[0][0][0]['facebookURL'];?>"><img src="/public/images/facebookwhite.gif" hspace="5" border="0" title="<?php echo $userDetailsArray[0][0][0]['facebookURL'];?>"/></a><div class="lineSpace_6"></div><?php } ?>
					<?php if(isset($userDetailsArray[0][0][0]['linkedInURL']) && $userDetailsArray[0][0][0]['linkedInURL']!=''){ ?><a href="<?php echo $userDetailsArray[0][0][0]['linkedInURL'];?>"><img src="/public/images/linkedIn.gif" hspace="5" border="0" title="<?php echo $userDetailsArray[0][0][0]['linkedInURL'];?>"/></a><div class="lineSpace_6"></div><?php } ?>
					<?php if(isset($userDetailsArray[0][0][0]['twitterURL']) && $userDetailsArray[0][0][0]['twitterURL']!=''){ ?><a href="<?php echo $userDetailsArray[0][0][0]['twitterURL'];?>"><img src="/public/images/twitter.gif" hspace="5" border="0" title="<?php echo $userDetailsArray[0][0][0]['twitterURL'];?>"/></a><div class="lineSpace_6"></div><?php } ?>
					<?php if(isset($userDetailsArray[0][0][0]['youtubeURL']) && $userDetailsArray[0][0][0]['youtubeURL']!=''){ ?><a href="<?php echo $userDetailsArray[0][0][0]['youtubeURL'];?>"><img src="/public/images/youtube.gif" hspace="5" border="0" title="<?php echo $userDetailsArray[0][0][0]['youtubeURL'];?>"/></a><div class="lineSpace_6"></div><?php } ?>
				</div>
			</div>
			<?php if(($userId==$viewedUserId)){ ?><div class="mb5 Fnt11" style="margin-top:5px;"><a href="/messageBoard/MsgBoard/expertOnboard">Edit details</a></div><?php } ?>
			<?php }} ?>
		</div>

		<div class="lineSpace_25">&nbsp;</div>
	</div>
	<div class="clear_B">&nbsp;</div>
</div>
<?php
} 
?>
