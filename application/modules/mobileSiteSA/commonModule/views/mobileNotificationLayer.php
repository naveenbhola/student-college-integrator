<section>
	<!-- <article class="profile-wrap">
        <div class="user-info content-inner">
        	<p class="user-name"><?php //echo htmlentities($name); ?></p>
        </div>
        <div class="clearfix"></div>
  	</article> -->
  	<div class="profile_slct">
	   <div class="user_wrap clear_max">
	      <!--pic placeholder-->
	      <div class="pic_around clearfix">
	         <div class="pic_col" style="background: url(<?php echo $profileImgUrl; ?>) center;"></div>
	      </div>
	      <!--end of pic placeholder-->
	      <div class="txt_wrap">
	         <h1 class="user_alias"><?php echo htmlentities($name); ?></h1>
	         <a class="rank_state pnl_a" href="<?php echo SHIKSHA_STUDYABROAD_HOME."/dashboard/my-counselor";?>">My Dashboard</a>
           <span>|</span>
            <a class="pnl_a" href="javascript:void(0);" onclick="SignOutUser(); return false;">Logout</a>
	      </div>
	   </div>
	</div>
</section>
<section class="profile-list">
    <article class="profile-inner" style="background:#fff;">
    	<ul class="clearfix">

      <li>
				<a href="Javascript:void(0);" onclick="$j('.dashboard-list').slideToggle(800);">
					<div class="myprofile-info">
						My Dashboard
            <i class="downArrow"></i>
					</div>
				</a>
				<ul class="dashboard-list" style="overflow-y: scroll;">
					<li><a href="<?php echo SHIKSHA_STUDYABROAD_HOME."/dashboard/my-counselor";?>">My Counselor</a></li>
					<?php if($isCandidate === true){?>
							<li><a href="<?php echo SHIKSHA_STUDYABROAD_HOME."/dashboard/my-documents";?>">My Documents</a></li>
					<?php } ?>
          			<li><a href="<?php echo SHIKSHA_STUDYABROAD_HOME."/dashboard/rate-my-chances";?>">
          				My Chances <span class="countTag"><?php echo $rmcCount; ?></span></a>
          			</li>
          			<li><a href="<?php echo SHIKSHA_STUDYABROAD_HOME."/dashboard/saved-courses";?>">
          				My Saved Courses <span class="countTag"><?php echo $shortListedCourses['count']; ?></span></a>
          			</li>

				</ul>
			</li>
			<li>
				<a href="Javascript:void(0);" onclick="$j('.notification-list').slideToggle(800);">
					<div class="myprofile-info">
						Notifications
            <i class="downArrow"></i>
						<strong>
							(
							<span style="margin-right:0" id="loggedInSlCount">
								<?php echo count($notificationData['notifications']); ?>
							</span>
							)
						</strong>
					</div>
				</a>
				<ul class="notification-list" style="overflow-y: scroll;">
					<?php
						foreach($notificationData['notifications'] as $notification){
							switch($notification['notificationType'])
							{
								case 'ratingGiven': $notificationCourse = $notification['listingTypeId'];
													$onclick = 'onclick="moveToPath(\''.SHIKSHA_STUDYABROAD_HOME."/dashboard/rate-my-chances?notification={$notificationCourse}".'\');"';
													break;
								default			  : $onclick = '';
													break;
							}
						?>
					<li class="pnl_a" <?=($onclick)?>>
						<p class="<?=(!$notification['isViewed']? 'newNotification':'oldNotification')?>"><?=($notification['notification'])?></p>
						<span><?=($notification['notificationTime'])?></span>
					</li>
					<?php } ?>
				</ul>
			</li>

        </ul>
    </article>
    </section>
<section>
	<!-- <article class="button-wrap  userActionButtons" style="">
		<a class="pnl_a" href="javascript:void(0);" onclick="SignOutUser(); return false;">Log Out</a>
	</article> -->
</section>
