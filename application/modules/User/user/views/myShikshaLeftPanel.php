<?php
	$loggedInUsername = isset($validateuser[0]['displayname'])?$validateuser[0]['displayname']:'';
	$displayName = isExist($userDetails['displayname'] , '');
	$shortDisplayName = (strlen($displayName)<= 18)?$displayName:substr($displayName,0,15)."...";
	$email = isExist($userDetails['email'] , '');
	$city = isExist($userDetails['city'] , '');
	$cityName = (strlen($city)<=18)?$city:substr($city,0,15)."...";
	$mobile = isExist($userDetails['mobile'] , '');
	$userProfession = isExist($userDetails['detail'] , '');
	$interestedIn = (isset($interestedIn) && !empty($interestedIn)) ? $interestedIn : 'MBA';
	$userType = (isset($userDetails['userLevel']) && !empty($userDetails['userLevel'])) ? $userDetails['userLevel'] : 'Beginner-Level 1';
	$points = (isset($userDetails['userPoints']) && !empty($userDetails['userPoints'])) ? $userDetails['userPoints'] : '0 Points';
	$userDisplayPic = isset($userDetails['avtarimageurl'])?getMediumImage($userDetails['avtarimageurl']):'';
	$newsletteremail = ($userDetails['newsletteremail'] == NULL) || ($userDetails['newsletteremail'] == 1) ? 'checked' : '';
	$viamobile = $userDetails['viamobile'] == 1 ? 'checked' : '';
	$viaemail = $userDetails['viaemail'] == 1 ? 'checked' : '';
	$publishInstituteFollowing = $userDetails['publishInstituteFollowing'] == 1 ? 'checked' : '';
	$publishInstituteUpdates = $userDetails['publishInstituteUpdates'] == 1 ? 'checked' : '';
	$publishRequestEBrochure = $userDetails['publishRequestEBrochure'] == 1 ? 'checked' : '';
	//$publishAnaActivity = $userDetails['publishAnaActivity'] == 1 ? 'checked' : '';
        $publishQuestionOnFB = $userDetails['publishQuestionOnFB'] == 1 ? 'checked' : '';
        $publishAnswerOnFB = $userDetails['publishAnswerOnFB'] == 1 ? 'checked' : '';
        $publishDiscussionOnFB = $userDetails['publishDiscussionOnFB'] == 1 ? 'checked' : '';
        $publishAnnouncementOnFB = $userDetails['publishAnnouncementOnFB'] == 1 ? 'checked' : '';
	$publishBestAnswerAndLevelActivity = $userDetails['publishBestAnswerAndLevelActivity'] == 1 ? 'checked' : '';
	$publishArticleFollowing = $userDetails['publishArticleFollowing'] == 1 ? 'checked' : '';
	
	function isExist($value, $defaultValue){
		return (isset($value) && !empty($value)) ? $value : $defaultValue;
	}
//	$userType = str_ireplace('Level', 'Member', $userType);
	$this->load->view('user/uploadMyImage', array('userDisplayPic'=>$userDisplayPic));
?>


	<!--Start_Left_Panel-->
	<?php if(strcmp($editFlag,'false')==0):
		$leftPanelHeight = 800;
	 endif; ?>
	<div id="left_Panel" style="height:<?php echo $leftPanelHeight; ?>px;margin-left:0px;">
			<!--Course_TYPE-->
			<div class="raised_blue_L">
			<b class="b2"></b>
                <div class="boxcontent_blue">
                  <div class="lineSpace_5">&nbsp;</div>
				  <div class="bld fontSize_14p mar_left_5p" align="center">
				  	<label title="<?php echo $displayName; ?>">
						<?php echo insertWbr($displayName,10); ?>
					</label>
				  </div>
				  
                  <div class="lineSpace_5">&nbsp;</div>
				  <div align="center">
				  	<a onmouseover='showHelp(event,this);' onmouseout='hideHelp();' onmousemove='showHelp(event,this)' ftip="userLevel"  href="/shikshaHelp/ShikshaHelp/upsInfo" class="OrgangeFont bld pd_full brdGreen" style="text-decoration:none">
				  		<?php echo $userType; ?>
				  	</a>
				  </div>
				  <div class="lineSpace_10">&nbsp;</div>
				  <div align="center">
				  	<img id="userImage" name="userImage" src="<?php echo $userDisplayPic;?>" /><br /><br />
					<?php if(strcmp($editFlag,'true')==0): ?>
				  	<span id="photoControl" class="myProfile">
				  		<a href="#" onClick="return showUploadMyImage('updateMyImageOverlay');">edit</a>
				  	</span>
					<?php endif; ?>
				  </div>
				  <div align="center" id="linkFbAccount1" style="display:none;height:0px;">
					<!--Use facebook picture.
				  <a class="header-fb" href="javascript:void(0);" onclick="eventTriggered = 'AccountAndSettings'; showOverlayForFConnect();"></a>-->
				  </div>
				  <div align="left" class="mar_left_5p">
					<?php if(strcmp($editFlag,'false')==0):
						if(($loggedInUserId != $userId) || ($loggedInUserId == 0)):
							$userStatus = getUserStatus($userDetails['lastlogintime']);
					?>
					<!-- Network bar start here -->
						<img src="<?php echo $userStatus; ?>" title="<?php echo getUserStatusToolTip($userStatus,$displayName,$userDetails['lastlogintime']); ?>" hspace="4"/>&nbsp;&nbsp;<?php echo $userDetails['userStatusMsg'];  ?>
	<br /><br /><img src="/public/images/mail.gif" hspace="4"
onClick = "showMailOverlay('<?php echo $loggedInUserId; ?>','<?php echo $loggedInUsername ?>','<?php echo $userId; ?>','<?php echo $userDetails['displayname'] ?>','SHIKSHA_MYSHIKSHA_LEFTPANEL_MAILUSER', this)" title = "<?php echo MAIL_TO_USER.$displayName;?>" />&nbsp;Send email
	<br /><br />
	<?php if(!in_array($loggedInUserId,$userFriends)):  ?>
	<img src="/public/images/plus.gif" hspace="4"
onClick = "sendRequest('<?php echo $loggedInUserId; ?>','<?php echo $userId; ?>','SHIKSHA_MYSHIKSHA_LEFTPANEL_SENDREQUEST', this)" title = "<?php echo ADD_TO_NETWORK.$displayName;?>" />&nbsp;&nbsp;Add to contact list
	<?php else:
		echo "&nbsp;<img src=\"/public/images/network-alreadin.gif\" title=\"".$displayName.ALREADY_ADDED_TO_NETWORK."\"/>&nbsp;&nbsp;Add to contact list";
	 endif; ?>

					<!-- Network bar ends here -->
					<?php  else:
						echo '<br /><br /><img src="/public/images/you_icon.gif" />&nbsp;Your own Profile';
					      endif;
					endif;
					?>
				  </div>
				  <div class="lineSpace_15">&nbsp;</div>
		<?php if(strcmp($editFlag,'true')==0): ?>
		                  <div class="myProfile row">
					<div class="normaltxt_11p_blk OrgangeFont mar_left_10p">Shiksha Points</div>
					<div class="lineSpace_1">&nbsp;</div>
				  </div>
				  <div class="myProfile normaltxt_11p_blk mar_left_10p" style="word-wrap:break-word;">
				  	<a href="/shikshaHelp/ShikshaHelp/upsInfo" title="<?php echo $points; ?>" style="text-decoration:none;cursor:pointer;color:#000000;">
						<?php if(strlen($points)< 18)
							echo $points;
						      else
							echo substr($points,0,18)."...";
						?>
					</a>
				  </div>
				  <div class="lineSpace_10">&nbsp;</div>
				  <div class="myProfile row">
				  	<!--<div id="emailIdControl" class="float_R mar_right_5p myProfile">
				  		<a href="#" onClick="return controlField(this);">edit</a>
				  	</div> -->
					<div class="normaltxt_11p_blk OrgangeFont mar_left_10p">Email-ID</div>
					<div class="lineSpace_1">&nbsp;</div>
				  </div>
				  <div class="myProfile normaltxt_11p_blk mar_left_10p" style="word-wrap:break-word;">
				  	<a href="javascript:void(0);" title="<?php echo $email; ?>" style="text-decoration:none;cursor:pointer;color:#000000;">
						<?php if(strlen($email)< 19)
							echo $email;
						      else
							echo substr($email,0,18)."...";
						?>
					</a>
				  	<!--<input type="text" id="emailId" name="emailId" class="myShikshaTextboxLabel" readonly=true value="<?php echo $email; ?>"/> -->
				  </div>
				  <div class="lineSpace_10">&nbsp;</div>
		<?php endif; ?>
				  <div class="row">
					<?php if(strcmp($editFlag,'true')==0): ?>
				  	<div id="displayNameControl" class="float_R mar_right_5p myProfile">
				  		<a href="#" onClick="return controlField(this);">edit</a>
				  	</div>
					<?php endif; ?>
					<div class="normaltxt_11p_blk OrgangeFont mar_left_10p">Display Name</div>
				  </div>
				  <div class="lineSpace_1">&nbsp;</div>
				  <div class="normaltxt_11p_blk mar_left_10p">
				  	<input type="text" id="displayName" name="displayName" fieldDisplay="display name" minlength="3" maxlength="25" required="true" typeOfField="string" class="myShikshaTextboxLabel" title="<?php echo $displayName; ?>"  value="<?php echo $shortDisplayName; ?>" onkeyup="if(event.keyCode == 13){ return controlField(document.getElementById('saveLinkIdForMyShikshadisplayName')); }" readonly="true" />
				  </div>
				  <div class="errorPlace">
					<div id="displayName_error" class="errorMsg"></div>
		 		  </div>
				  <div class="lineSpace_10">&nbsp;</div>

				  <div class="row">
					<?php if(strcmp($editFlag,'true')==0): ?>
				  	<div id="userCityControl" class="float_R mar_right_5p myProfile">
				  		<a href="#" onClick="return controlField(this)">edit</a>
				  	</div>
					<?php endif; ?>
					<div class="normaltxt_11p_blk OrgangeFont mar_left_10p">City</div>
				  </div>
				  <div class="lineSpace_1">&nbsp;</div>
				  <div class="normaltxt_11p_blk mar_left_10p">
				  	<input type="text" id="userCity" name="userCity" fieldDisplay="city name" minlength="3" maxlength="25" required="true" typeOfField="stringCity" class="myShikshaTextboxLabel" title="<?php echo $city ?>"  value="<?php echo $cityName; ?>" onkeyup="if(event.keyCode == 13){ return controlField(document.getElementById('saveLinkIdForMyShikshauserCity')); }" readonly="true" />
				 </div>
				  <div class="errorPlace">
					<div id="userCity_error" class="errorMsg"></div>
		 		  </div>
				  <div class="lineSpace_10">&nbsp;</div>
				<?php if(strcmp($editFlag,'true')==0): ?>
				  <div class="myProfile row">
				  	<div id="mobileControl" class="float_R mar_right_5p myProfile">
				  		<!--a href="#" onClick="return controlField(this);">edit</a-->
				  	</div>
					<div class="normaltxt_11p_blk OrgangeFont mar_left_10p">Mobile No</div>
				  <div class="lineSpace_1">&nbsp;</div>
				  </div>
				  <div class="myProfile normaltxt_11p_blk mar_left_10p">
				  	<input type="text" id="mobile" name="mobile" class="myShikshaTextboxLabel" fieldDisplay="Mobile No." minlength="10" maxlength="10" required="true" typeOfField="ext" onkeyup="if(event.keyCode == 13){ return controlField(document.getElementById('saveLinkIdForMyShikshamobile')); }" value="<?php echo $mobile; ?>" readonly="true" />
				  </div>
				  <div class="errorPlace">
					<div id="mobile_error" class="errorMsg"></div>
		 		  </div>
				  <div class="lineSpace_10">&nbsp;</div>
				<?php endif; ?>
                </div>
			  <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
			</div>
			<div class="lineSpace_10">&nbsp;</div>

			<!--MyAccount-->
			<?php if(strcmp($editFlag,'true')==0): ?>
			<div class="raised_blue_L myProfile">
			<b class="b2"></b>
                <div class="boxcontent_country" style="padding:5px;">
                  <div class="lineSpace_5">&nbsp;</div>
				  <div class="normaltxt_11p_blk bld mar_left_5p">My Account Settings</div>
				  <div class="lineSpace_5">&nbsp;</div>

				  <div id="changePasswordForm" style="display:none">

				  	<div class="row">
						<div class="normaltxt_11p_blk OrgangeFont mar_left_10p">Current Password</div>
				  	</div>
				  	<div class="lineSpace_1">&nbsp;</div>
					<?php
					        echo $this->ajax->form_remote_tag( array(
					                    'url' => base_url().'/user/MyShiksha/changePassword',
					                    'success' => 'javascript:updateUserAttributeResponse(request.responseText);',
					                    'failure'=>'javascript:updateUserAttributeResponse(request.responseText)')
					            );
					    ?>
				  	<div class="normaltxt_11p_blk mar_left_10p">
				  		<input type="password" id="currentPassword" name="currentPassword" required="true" validate="validateStr" maxlength="20" minlength="5" caption="Current password" class="myShikshaTextboxblueBorder" onkeyup="if(event.keyCode == 13){ return controlChangePassword(document.getElementById('userChangePass')); }" />
				  	</div>
					<div class="errorPlace">
						<div id="currentPassword_error" class="errorMsg"></div>
		 			</div>
				  	<div class="lineSpace_10">&nbsp;</div>

				  	<div class="row">
						<div class="normaltxt_11p_blk OrgangeFont mar_left_10p">New Password</div>
				  	</div>
				  	<div class="lineSpace_1">&nbsp;</div>
				  	<div class="normaltxt_11p_blk mar_left_10p">
				  		<input type="password" id="newPassword" name="newPassword" required="true" validate="validateStr" maxlength="20" minlength="5" caption="New password" class="myShikshaTextboxblueBorder" onkeyup="if(event.keyCode == 13){ return controlChangePassword(document.getElementById('userChangePass')); }" />
				  	</div>
					<div class="errorPlace">
						<div id="newPassword_error" class="errorMsg"></div>
		 			</div>
				  	<div class="lineSpace_10">&nbsp;</div>

				  	<div class="row">
						<div class="normaltxt_11p_blk OrgangeFont mar_left_10p">Confirm New Password</div>
				  	</div>
				  	<div class="lineSpace_1">&nbsp;</div>
				  	<div class="normaltxt_11p_blk mar_left_10p">
				  		<input type="password" id="confirmPassword" name="confirmPassword" maxlength="20" class="myShikshaTextboxblueBorder" onkeyup="if(event.keyCode == 13){ return controlChangePassword(document.getElementById('userChangePass')); }" />
				  	</div>
						<div id="passwordmatch_error" class="errorMsg"></div>
				  	<div class="lineSpace_10">&nbsp;</div>
				  	<div class="mar_left_15p">
				  		<a href="#" onClick="return controlChangePassword(this);" id="userChangePass">Update</a>
				  		&nbsp;
				  		<a href="#" onClick="return controlChangePassword(this);">Cancel</a>
				  	</div>
					</form>
				  	<div class="lineSpace_10">&nbsp;</div>
					<div class="mar_left_5p" id="changePassMsg"></div>
				  </div>

				  <div class="mar_left_5p" id="changePasswordBlock">
				  	<a href="#" onClick="return controlChangePassword(this);">Change Password</a>
					<div id="showChangePassMsg"></div>
				  </div>
                </div>
			  <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
			</div>
			<?php endif; ?>
			<!--End_My_Acccount-->
			<div class="lineSpace_10">&nbsp;</div>

			<!--Privacy_Setting-->
			<?php if(strcmp($editFlag,'true')==0): ?>
			<div class="raised_blue_L  myProfile notMentorshipProgram" <?php if($mentorshipStatus){ echo "style='display:none;'";}?>>
			<b class="b2"></b>
                <div class="boxcontent_country">
                  <div class="lineSpace_5">&nbsp;</div>
				  <div class="normaltxt_11p_blk bld mar_left_5p">Privacy Settings</div>
				  <div class="lineSpace_10">&nbsp;</div>
				  <div class="row">
				  	<div class="bld mar_left_5p float_L"><input type="checkbox" value="updateCheckBoxField(this);" id="contactByMobile" name="contactByMobile" onClick="updateCheckBoxField(this,'privacySettingsResponse');" <?php echo $viamobile; ?>/></div>
					<div class="normaltxt_11p_blk mar_right_10p mar_left_30p">I wish to be contacted via Mobile</div>
				  </div>
				  <div class="lineSpace_10">&nbsp;</div>
				  <div class="row">
				  	<div class="bld mar_left_5p float_L"><input type="checkbox" value="1" id="contactByEmail" name="contactByEmail" onClick="updateCheckBoxField(this,'privacySettingsResponse');" <?php echo ($userDetails['unsubscribeFlag'] ==0 ? 'checked' : ''); ?>/></div>
					<div class="normaltxt_11p_blk mar_right_10p mar_left_30p">I wish to be contacted via Email</div>
				  </div>
				  <!-- <div class="lineSpace_10">&nbsp;</div>
				  <div class="row">
				  	<div class="bld mar_left_5p float_L"><input type="checkbox" value="1" id="sendNewsLetterByEmail" name="sendNewsLetterByEmail" onClick="updateCheckBoxField(this,'privacySettingsResponse');" <?php echo $newsletteremail; ?>/></div>
					<div class="normaltxt_11p_blk mar_right_10p mar_left_30p">I wish to recieve newsletters via Email</div>
				  </div> -->
				  <div id="privacySettingsResponse" class="row" style="display:none;margin-top:10px;">
				  	<div class="bld mar_left_5p float_L">Settings Updated</div>
				  	<div class="normaltxt_11p_blk mar_right_10p mar_left_30p">&nbsp;</div>
				  </div>

				  
				  <div class="lineSpace_10">&nbsp;</div>
                </div>
			  <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
			</div>
			<div class="lineSpace_10">&nbsp;</div>
			<!--Facebook_Setting-->
			
			<!--<div class="raised_blue_L  myProfile">
			<b class="b2"></b>			
                <div class="boxcontent_country">
                  <div class="lineSpace_5">&nbsp;</div>
				  <div class="normaltxt_11p_blk bld mar_left_5p">Facebook Settings</div>
				  <div class="lineSpace_10">&nbsp;</div>
				  <div class="row">
				  	<div class="bld mar_left_5p float_L"><input type="checkbox" value="1" id="publishInstituteFollowing" name="publishInstituteFollowing" onClick="updateCheckBoxField(this,'privacySettingsResponse_facebook');" <?php echo $publishInstituteFollowing; ?>/></div>
					<div class="normaltxt_11p_blk mar_right_10p mar_left_30p">Publish Updates from Institutes I’m following</div>
				  </div>
				  <div class="lineSpace_10">&nbsp;</div>
				  <div class="row">
				  	<div class="bld mar_left_5p float_L"><input type="checkbox" value="1" id="publishInstituteUpdates" name="publishInstituteUpdates" onClick="updateCheckBoxField(this,'privacySettingsResponse_facebook');" <?php echo $publishInstituteUpdates; ?>/></div>
					<div class="normaltxt_11p_blk mar_right_10p mar_left_30p">Inform my network when I start following an institute</div>
				  </div>
				  <div class="lineSpace_10">&nbsp;</div>
				  <div class="row">
				  	<div class="bld mar_left_5p float_L"><input type="checkbox" value="1" id="publishRequestEBrochure" name="publishRequestEBrochure" onClick="updateCheckBoxField(this,'privacySettingsResponse_facebook');" <?php echo $publishRequestEBrochure; ?>/></div>
					<div class="normaltxt_11p_blk mar_right_10p mar_left_30p">Publish update when I request an e-brochure</div>
				  </div>-->
				  <!--<div class="lineSpace_10">&nbsp;</div>
				  <div class="row">
				  	<div class="bld mar_left_5p float_L"><input type="checkbox" value="1" id="publishAnaActivity" name="publishAnaActivity" onClick="updateCheckBoxField(this,'privacySettingsResponse_facebook');" <?php //echo $publishAnaActivity; ?>/></div>
					<div class="normaltxt_11p_blk mar_right_10p mar_left_30p">Publish my new question/answer/discussion on my wall</div>
				  </div>-->
                                  <!--<div class="lineSpace_10">&nbsp;</div>
				  <div class="row">
				  	<div class="bld mar_left_5p float_L"><input type="checkbox" value="1" id="publishQuestionOnFB" name="publishQuestionOnFB" onClick="updateCheckBoxField(this,'privacySettingsResponse_facebook');" <?php echo $publishQuestionOnFB; ?>/></div>
					<div class="normaltxt_11p_blk mar_right_10p mar_left_30p">Publish my new question on my wall</div>
				  </div>
                                  <div class="lineSpace_10">&nbsp;</div>
				  <div class="row">
				  	<div class="bld mar_left_5p float_L"><input type="checkbox" value="1" id="publishAnswerOnFB" name="publishAnswerOnFB" onClick="updateCheckBoxField(this,'privacySettingsResponse_facebook');" <?php echo $publishAnswerOnFB; ?>/></div>
					<div class="normaltxt_11p_blk mar_right_10p mar_left_30p">Publish my new answer on my wall</div>
				  </div>
                                  <div class="lineSpace_10">&nbsp;</div>
				  <div class="row">
				  	<div class="bld mar_left_5p float_L"><input type="checkbox" value="1" id="publishDiscussionOnFB" name="publishDiscussionOnFB" onClick="updateCheckBoxField(this,'privacySettingsResponse_facebook');" <?php echo $publishDiscussionOnFB; ?>/></div>
					<div class="normaltxt_11p_blk mar_right_10p mar_left_30p">Publish my new discussion on my wall</div>
				  </div>
                                  <div class="lineSpace_10">&nbsp;</div>
				  <div class="row">
				  	<div class="bld mar_left_5p float_L"><input type="checkbox" value="1" id="publishAnnouncementOnFB" name="publishAnnouncementOnFB" onClick="updateCheckBoxField(this,'privacySettingsResponse_facebook');" <?php echo $publishAnnouncementOnFB; ?>/></div>
					<div class="normaltxt_11p_blk mar_right_10p mar_left_30p">Publish my new announcement on my wall</div>
				  </div>
				  <div class="lineSpace_10">&nbsp;</div>
				  <div class="row">
				  	<div class="bld mar_left_5p float_L"><input type="checkbox" value="1" id="publishBestAnswerAndLevelActivity" name="publishBestAnswerAndLevelActivity" onClick="updateCheckBoxField(this,'privacySettingsResponse_facebook');" <?php echo $publishBestAnswerAndLevelActivity; ?>/></div>
					<div class="normaltxt_11p_blk mar_right_10p mar_left_30p">Publish my AnA level progression/Best Answers</div>
				  </div>
				  <div class="lineSpace_10">&nbsp;</div>
				  <div class="row">
				  	<div class="bld mar_left_5p float_L"><input type="checkbox" value="1" id="publishArticleFollowing" name="publishArticleFollowing" onClick="updateCheckBoxField(this,'privacySettingsResponse_facebook');" <?php echo $publishArticleFollowing; ?>/></div>
					<div class="normaltxt_11p_blk mar_right_10p mar_left_30p">Publish articles I'm following</div>
				  </div>
				  <div class="lineSpace_10">&nbsp;</div>
				  <div id = "linkFbAccount" style="display:none">
				  <script src="https://connect.facebook.net/en_US/all.js"></script>
				  <div id = "FConnect_Account_Direct" style="display:none">
				  <fb:login-button scope="email,user_checkins,offline_access,read_stream,publish_stream" on-login="onLoginFromFacebook('AccountAndSettings');hideOverlay();">Sign In With FB</fb:login-button>
				  </div>
				<!--<input type="button" class="fcnt_signBtn pointer" value="&nbsp;" onclick="eventTriggered = 'AccountAndSettings'; showOverlayForFConnect();"/>-->
				<!--<div id = "FConnect_Account_Indirect" style="display:none;text-align:center">
					Link facebook account.
				<a class="header-fb" style="margin-left:35px;" href="javascript:void(0);" onclick="eventTriggered = 'AccountAndSettings'; showOverlayForFConnect();"></a>
				</div>
 				  </div>
				  <div id = "DlinkFbAccount" style="display:none;margin-left:5px">
					<a href="javascript:void(0)" onClick = "deleteAccessToken('<?php echo $userId;?>','AccountAndSettings')">Delink Facebook Account</a>
 				  </div> 	
				  <div id="privacySettingsResponse_facebook" class="row" style="display:none;margin-top:10px;">
				  	<div class="bld mar_left_5p float_L">Settings Updated</div>
				  	<div class="normaltxt_11p_blk mar_right_10p mar_left_30p">&nbsp;</div>
				  </div>
				  <div class="lineSpace_10">&nbsp;</div>
                </div>
			  <b class="b4b"></b><b class="b3b"></b><b class="b2b"></b><b class="b1b"></b>
			</div>-->
			<!--<div id="FacebookLoginButton" style="display:none">
			<div id="fb-root"></div>
			<div>
			<fb:login-button perms="email,user_checkins,publish_stream,offline_access" on-login=onLoginFromFacebook('request-E-Brochure') > Link Facebook </fb:login-button>
			</div>
			</div>-->
			<!--Facebook_Setting-->
			<?php endif; ?>
			<!--End_Privacy_Setting-->
			<?php // } ?>
	</div>
	<?php
        echo $this->ajax->form_remote_tag( array(
                    'url' => base_url().'/user/MyShiksha/updateUser/1',
                    'success' => 'javascript:updateUserAttributeResponse(request.responseText);',
                    'failure'=>'javascript:updateUserAttributeResponse(request.responseText)')
            );
    ?>
    	<input type="hidden" name="attributeName" id="attributeName" value=""/>
    	<input type="hidden" name="attributeValue" id="attributeValue" value=""/>
    </form>
	<!--End_Left_Panel-->
<script>
    checkForLinkDLinkOption('<?php echo $userId;?>','AccountAndSettings');
    buttonForFConnectAndFShare();
</script>
