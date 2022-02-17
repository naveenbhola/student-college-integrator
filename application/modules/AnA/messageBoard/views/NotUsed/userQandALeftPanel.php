<?php 
	$loggedInUsername = isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:'';
	$loggedInUserId = isset($validateuser[0]['userid'])?$validateuser[0]['userid']:'';	
	$displayName = $userDetails['displayname'];
	//$shortDisplayName = (strlen($displayName)<= 18)?$displayName:substr($displayName,0,15)."...";
	$email = $userDetails['email'];
	$city = $userDetails['city'];
	$mobile = $userDetails['mobile'];
	$userLevel = (isset($userDetails['userLevel']) && !empty($userDetails['userLevel'])) ? $userDetails['userLevel'] : 'Bronze Level';
	$points = (isset($userDetails['userPoints']) && !empty($userDetails['userPoints'])) ? $userDetails['userPoints'].' Points' : '100 Points';
	$userDisplayPic = isset($userDetails['avtarimageurl'])?getMediumImage($userDetails['avtarimageurl']):'';
	$userProfile = site_url('getUserProfile').'/'.$displayName;
	$totalQuestionsHeading = ($totalQuestions <= 1)?'Question Asked:':'Questions Asked:';
	$totalAnswersHeading = ($totalAnswers <= 1)?'Comment given:':'Comments given:';
?>
 <div style="width:154px; float:left">
		<div class="raised_blue_L">
			<b class="b2"></b>
			<div class="boxcontent_blue">
				<div style="line-height:7px">&nbsp;</div>
				<div class="txt_align_c"><a href="<?php echo $userProfile; ?>" class="fontSize_14p" title="<?php echo $displayName; ?>"><?php echo insertWbr($displayName,10); ?></a></div>
				<div style="line-height:7px">&nbsp;</div>
				<div style="line-height:22px" class="txt_align_c"><a href="/shikshaHelp/ShikshaHelp/upsInfo" class="OrgangeFont bld pd_full brdGreen" style="text-decoration:none"><?php echo $userLevel; ?></a></div>
				<div style="line-height:7px">&nbsp;</div>
				<div class="txt_align_c" style="width:117px; height:82px;margin:auto"><img src="<?php echo $userDisplayPic; ?>" /> </div>
				<div align="left" class="mar_left_5p">
					<?php 	if(($loggedInUserId != $viewedUserId) || ($loggedInUserId == 0)): 
						$userStatus = getUserStatus($userDetails['lastlogintime'])
					?>		
					<!-- Network bar start here -->	
						<img src="<?php echo $userStatus; ?>" title="<?php echo getUserStatusToolTip($userStatus,$displayName,$userDetails['lastlogintime']); ?>" hspace="4"/>&nbsp;&nbsp;<?php echo $userDetails['userStatusMsg'];  ?>
	<br /><br /><img src="/public/images/mail.gif" hspace="4" title = "<?php echo MAIL_TO_USER.$displayName; ?>" style="cursor:pointer;"
onClick = "javascript:showMailOverlay('<?php echo $loggedInUserId; ?>','<?php echo $loggedInUsername; ?>','<?php echo $viewedUserId; ?>','<?php echo $displayName; ?>','ASK_USERQNA_LEFTPANEL_MAILUSER',this);" />&nbsp;Send email
	<br /><br />
	<?php if(!in_array($loggedInUserId,$userFriends)):  ?>
	<img src="/public/images/plus.gif" hspace="4" title = "<?php echo ADD_TO_NETWORK.$displayName;?>" style="cursor:pointer;"
onClick = "javascript:sendRequest('<?php echo $loggedInUserId; ?>','<?php echo $viewedUserId; ?>','ASK_USERQNA_LEFTPANEL_ADDUSER',this)" />&nbsp;&nbsp;Add to contact list		
	<?php else: 
		echo "&nbsp;<img src=\"/public/images/network-alreadin.gif\" title=\"".$displayName.ALREADY_ADDED_TO_NETWORK."\"/>&nbsp;&nbsp;Add to contact list";
	 endif; ?>
	
					<!-- Network bar ends here -->	
					<?php  else:
						echo '<br /><br /><img src="/public/images/you_icon.gif" />&nbsp;Your own Profile';
					      endif;	
					?>
				  </div>	
				
				<div class="lineSpace_28p">&nbsp;</div>
				<div class="mar_left_10p">
				<div class="normaltxt_11p_blk OrgangeFont">Display Name</div><br />
				<span class="fontSize_12p" title="<?php echo $displayName; ?>"><?php echo insertWbr($displayName,10); ?></span>
				<div class="lineSpace_10p">&nbsp;</div>
				<div class="normaltxt_11p_blk OrgangeFont">City</div><br />
				<span class="fontSize_12p"><?php echo $city; ?></span>	
				</div>
				<div style="line-height:7px">&nbsp;</div>
				<div align="center">
					<div class="boxAccunt" align="left">
						<div style="line-height:7px">&nbsp;</div>
						<div class="mar_left_5p">
						<b><?php echo $totalQuestionsHeading; ?></b><br />
						<span class="OrgangeFont bld"><?php echo $totalQuestions; ?></span><br />
						<div class="lineSpace_10p">&nbsp;</div>
						<b><?php echo $totalAnswersHeading; ?></b><br />
						<span class="OrgangeFont bld"><?php echo $totalAnswers; ?></span><br />
						</div>
					</div>
				</div>							
			</div>
			<b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
		</div>
	</div> 
