<?php 
	$mainLeaderInfo = $leaderBoardInfo;
	$leaderBoardInfo = $leaderBoardInfo['msgArray'][0];
	$otherUserDetails = array();
	$weeklyAnAPoints = 0;
	$weeklyParticipatePoints = 0;
	//Get the Other User details like Count of discussions, count of announcements, total participation points
	if(isset($mainLeaderInfo['otherUserDetails']) && is_array($mainLeaderInfo['otherUserDetails'])){
	  $otherUserDetails = $mainLeaderInfo['otherUserDetails'][0];
	}
	//Get the Weekly points for AnA and Participate
	if(isset($mainLeaderInfo['otherUserDetails'][1]['weeklyPoints'])){
	  $weeklyAnAPoints = $mainLeaderInfo['otherUserDetails'][1]['weeklyPoints'];
	}else if(isset($mainLeaderInfo['otherUserDetails'][1]['weeklyParticipatePoints'])){
	  $weeklyParticipatePoints = $mainLeaderInfo['otherUserDetails'][1]['weeklyParticipatePoints'];
	}
	if(isset($mainLeaderInfo['otherUserDetails'][2]['weeklyParticipatePoints'])){
	  $weeklyParticipatePoints = $mainLeaderInfo['otherUserDetails'][2]['weeklyParticipatePoints'];
	}

	$shortDisplayName = (strlen($leaderBoardInfo['displayname']) > 14)?substr($leaderBoardInfo['displayname'],0,14).'...':$leaderBoardInfo['displayname'];

	function getStarClassForLeader($level)
	{
	    if($level!=''){
		switch($level){
		    case 'Advisor': return 'str_1lx33';
		    case 'Senior Advisor': return 'str_12x33';
		    case 'Lead Advisor': return 'str_13x33';
		    case 'Principal Advisor': return 'str_14x33';
		    case 'Chief Advisor': return 'str_15x33';
		}
	    }
	}
?>

    <!--Start_UserWidge-->
    <div class="wdh100">
	    <div class="leaderBoard-pannel">
		    <div style="background:#fff url(/public/images/bgNewAnAW.gif) left bottom repeat-x">
		    <div class="mlr5 pt5">
			    <div class="float_L w58">
					<div class="float_L wdh100">
						<a href="<?php echo SHIKSHA_HOME;?>/getUserProfile/<?php echo $leaderBoardInfo['displayname']; ?>"><img src="<?php if($leaderBoardInfo['avtarimageurl']=='') echo getSmallImage('/public/images/photoNotAvailable.gif'); else echo getSmallImage($leaderBoardInfo['avtarimageurl']); ?>" border="0" id="userImage"/></a>
						<?php if($leaderBoardInfo['isAnAExpert']!='1'){ ?><div class="Fnt11 txt_align_c">[ <a href="javascript:void(0);" onClick="return showUploadMyImage('updateNameImageOverlay','Edit your profile photo here');">change</a> ]</div><?php } ?>
						<input type="hidden" id="uploadImageText" value="" />
					</div>
			    </div>
			    <div class="ml70">
					<div class="float_L wdh100 <?php echo getStarClassForLeader($leaderBoardInfo['level']); ?>">
						<span class="Fnt14 bld" title="Click to view your profile"><a href="<?php echo SHIKSHA_HOME;?>/getUserProfile/<?php echo $leaderBoardInfo['displayname']; ?>" ><?php echo $shortDisplayName; ?></a></span><br/>
						<span class="Fnt11">[ <a href="javascript:void(0);" onClick="showUpdateUserNameImage('Edit your display name here','','','',0);">edit</a> ]</span>
						<div class="lineSpace_5">&nbsp;</div>
						<span class="Fnt11" style="margin-top:5px;"><a href="/shikshaHelp/ShikshaHelp/upsInfo" style="color:#000000;"><?php echo ($leaderBoardInfo['level']!='')?$leaderBoardInfo['level']:'Beginner'; ?></a> - Q&amp;A</span><br />
						<div class="Fnt11 pt3" >Member since: <?php echo $leaderBoardInfo['creationDate']; ?></div>
                        
					</div>
			    </div>
		    </div>
		    <div class="clear_B mb8">&nbsp;</div>
		    <div class="lineSpace_5">&nbsp;</div>
		    <div class="mlr5">
			    <div class="stats-cont">
				<div class="boxcontent_skyWithBGW">
				    <div class="mlr10 pt2 Fnt11">
					    <div class="bld mb5 Fnt12" >Contribution Stats</div>  
					    <div class="ana_a">
						    <div class="lineSpace_20">
							<?php echo $leaderBoardInfo['likes']; ?> <img src="/public/images/hUp.gif" /> upvotes<br />
							<a href="/messageBoard/MsgBoard/discussionHome/1/4/1/answer" style="color:#000"><?php echo $leaderBoardInfo['answerCount']; ?></a> <?php echo ($leaderBoardInfo['answerCount']>1)?'Answers':'Answer'; ?><br />
							<a href="/shikshaHelp/ShikshaHelp/upsInfo" style="color:#000;"><?php echo $leaderBoardInfo['userPointValue']; ?></a> Points (<?php echo $weeklyAnAPoints; ?> this week)<br />
                                                        
                                                    </div>
					    </div>
					    <div class="ln">&nbsp;</div>
					    <?php if($otherUserDetails['discussionPosts']>=1){ ?><div class="ana_blog mb5 lineSpace_20"><?php echo $otherUserDetails['discussionPosts']; ?> discussion <?php echo ($otherUserDetails['discussionPosts']>1)?'posts':'post'; ?></div><?php } ?>
					    <?php if($otherUserDetails['announcementPosts']>=1){ ?><div class="ana_mike mb5 lineSpace_20"><?php echo $otherUserDetails['announcementPosts']; echo ($otherUserDetails['announcementPosts']>1)?' announcements':' announcement'; ?></div><?php } ?>
					    <div class="pb8 lineSpace_20"><a href="/shikshaHelp/ShikshaHelp/upsInfo" style="color:#000;"><?php echo ($otherUserDetails['totalParticipatePoints']=='')?0:$otherUserDetails['totalParticipatePoints']; ?></a> Cafe points (<?php echo $weeklyParticipatePoints; ?> this week)</div>
					    <div class="clear_B">&nbsp;</div>                                        
				    </div>
				</div>
				</div>                            
		    </div>
		    <div class="mlr10" style="margin-top:3px;">
			<!-- Show the following person link Start -->
			<?php if(isset($followUser) && is_array($followUser) && count($followUser)==1){ ?>
			      <span class="Fnt11 lineSpace_20"><a href="javascript:void(0);" onclick="javascript:try { showFollowingPersons('<?php echo $userId;?>'); return false;} catch(e) {}">1 Follower</a></span>
			<?php }else if(isset($followUser) && is_array($followUser) && count($followUser)>1){ ?>
			      <span class="Fnt11 lineSpace_20"><a href="javascript:void(0);" onclick="javascript:try { showFollowingPersons('<?php echo $userId;?>'); return false;} catch(e) {}"><?php echo count($followUser);?> Followers</a></span>
			<?php } ?>
			<!-- Show the following person link End -->
		    </div>
		    <div class="clear_B">&nbsp;</div>
		</div>
		
	    </div>
	    <!--Start_VCard widget-->
	    <?php if(isset($cardStatus) && ($cardStatus=='1')) { ?>
	    <div class="mlr15">
		<div class="raised_skyWithBGW">
		    <div class="boxcontent_skyWithBGW" style="background:#6492ce"><div align="center" class="Fnt11 fcWht lineSpace_16">
			<?php if($cardStatus=='0'){ ?>
			<a href="/messageBoard/MsgBoard/vcardForm" style="color:#fff;">create your exclusive v-card</a>
			<?php }else if($cardStatus=='1'){ ?>
			<span style="color:#fff"><span onmouseover="showUserCommonOverlayForCard(this,'<?php echo $userId; ?>');" >view your v-card</span> [ <a href="/messageBoard/MsgBoard/expertOnboard" style="color:#fff">edit</a> ]</span>
			<?php } ?>
		    </div></div>
		    <b class="b4b" style="background:#6492ce"></b><b class="b3b" style="background:#6492ce"></b><b class="b2b" style="background:#6492ce"></b><b class="b1b"></b>
		</div>
	    </div>         			
	    <?php } ?>
	    <!--End_VCard widget-->
    </div>
    <!--End_UserWidge-->
