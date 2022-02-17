<?php
	if($userDetails === "false"){
		$userString = "!";
		$userId = -1;
	}else{
		$userString = " ".$userDetails[0]['firstname'].' '.$userDetails[0]['lastname'];
		$userId = $userDetails[0]['userid'];
	}
?>
<div id="shader" style="z-index:1001;display:none;background-color:black;opacity:0.4;top:0;left:0;position:fixed;"></div>
<div class="notification-layer" style="z-index:1002;display:none;position:fixed;">
    <div class="profile_slct notification-head">
        <div class="user_wrap clear_max">
            <!--pic placeholder-->
            <div class="pic_around clearfix">
                <div class="pic_col" style="background: url('<?php echo $profileImgUrl;?>')"></div>
            </div>
            <!--end of pic placeholder-->
            <div class="txt_wrap">
                <h1 class="user_alias"><?php echo $userString; ?></h1>
                <a class="rank_state" href="<?php echo SHIKSHA_STUDYABROAD_HOME."/dashboard/my-counselor";?>">My Dashboard</a>
								<span>|</span>
								<a href="javascript:void(0);" onclick="SignOutUser();">Logout</a>
            </div>
        </div>
    </div>
<!--	<div class="notification-head" style="">-->
<!--		<a class="remove-icon flRt" href="javascript:void(0);" onclick="closeNotificationOverlay();">&times;</a>-->
<!--		<div class="clearfix"></div>-->
<!--		<p class="notification-title">Hey --><?//=$userString?><!--</p>-->
<!---->
<!--	</div>-->

	<div class="notification-content">
		<div class="scrollbar1">
			<div style="right:10px" class="scrollbar">
				<div style="height:420px; z-index:1" class="track">
					<div style="z-index:1" class="thumb"></div>
				</div>
			</div>
			<div class="viewport" style="position: relative;">
				<div class="overview" style="width:99.5%;">
					<div class="notificationSection">
						<div class="notification-title-box" onclick="openCloseAccordion(this);" state="open" style="cursor:pointer;">
							My Dashboard
							<i class="downArrow"></i>
						</div>
						<ul class="dashboard-list">
							<li><a href="<?php echo SHIKSHA_STUDYABROAD_HOME."/dashboard/my-counselor";?>">My Counselor</a></li>
							<?php if($isCandidate){?>
							<li><a href="<?php echo SHIKSHA_STUDYABROAD_HOME."/dashboard/my-documents";?>">My Documents</a></li>
							<?php } ?>
							<li><a href="<?php echo SHIKSHA_STUDYABROAD_HOME."/dashboard/rate-my-chances";?>">
								My Chances <span class="countTag"><?php echo $rmcCount; ?></span></a>
							</li>
							<li><a href="<?php echo SHIKSHA_STUDYABROAD_HOME."/dashboard/saved-courses";?>">
								My Saved Courses <span class="countTag"><?php echo $shortListedCourses['count'];?></span></a>
							</li>
						</ul>
					</div>
					<div class="notificationSection">
						<div class="notification-title-box" onclick="openCloseAccordion(this);" state="open" style="cursor:pointer;">
							Notifications (<?=count($notificationData['notifications'])?>)
							<i class="downArrow"></i>
						</div>
						<?php if(count($notificationData['notifications'])>0){ ?>
							<ul class="notification-list">
								<?php foreach($notificationData['notifications'] as $notification){ ?>
									<?php
										$clickString = "";
										if($notification['notificationType'] == "ratingGiven"){
											$clickString = "onclick='sendToShortlistPage(".$notification['listingTypeId'].")'";
										}
									?>
									<li <?=$clickString?> style="cursor:pointer;">
										<?php if($notification['isViewed'] == "0"){ ?>
											<span class="notificationLayerBold"><?=htmlentities($notification['notification'])?></span>
										<?php }else{ ?>
											<span><?=($notification['notification'])?></span>
										<?php } ?>
										<span class="day-tag"><?=$notification['notificationTime']?></span>
									</li>
								<?php } ?>
							</ul>
						<?php }else{ ?>
							<div class="zero-result-sec">
                            	<p>You have no notifications. </p>
                            </div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="notification-btn-sec">
		<div class="clearfix"></div>
	</div>
</div>

<script>
	$j(".notification-list").children(":last").addClass("last");
	setTimeout(initializeNotificationLayer,10);

	function markNotificationsAsViewed() {
		if ($j(".notificationLayerBold").length == 0) {
			return true;
		}
		$j.ajax({
			url : '/studyAbroadCommon/AbroadNotifications/markNotificationsAsViewed/<?=$userId?>',
			method : '',
			success : function(res){
				$j(".notificationLayerBold").removeClass("notificationLayerBold");
				$j("#notificationCount").html('');
				$j(".notification-count").hide();
			}
		});
		return true;
	}

	function sendToShortlistPage(courseId){
		if (typeof(courseId) == "undefined") {
			window.location.href = "<?php echo SHIKSHA_STUDYABROAD_HOME."/dashboard/saved-courses";?>";
		}else{
			window.location.href = "<?php echo SHIKSHA_STUDYABROAD_HOME."/dashboard/rate-my-chances";?>?notification="+courseId;
		}
	}
</script>
<style>
	.notificationLayerBold {font-weight: bold;}
</style>
