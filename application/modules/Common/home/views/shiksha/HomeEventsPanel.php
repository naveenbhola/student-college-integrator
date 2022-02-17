<?php
	$eventsImage = isset($eventsImage) && $eventsImage != '' ? $eventsImage : '/public/images/foreign-edu-calender.jpg';
	$notificationsImage = isset($notificationsImage) && $notificationsImage != '' ? $notificationsImage : '/public/images/foreign-edu-calender.jpg';
	$latestUpdatesImage = isset($latestUpdatesImage) && $latestUpdatesImage != '' ? $latestUpdatesImage : '/public/images/foreign-edu-calender.jpg';
	
	$newsCaption = isset($newsCaption) && $newsCaption != '' ? $newsCaption : 'Latest';
	$notificationCaption = isset($notificationCaption) && $notificationCaption != '' ? $notificationCaption : 'Announcements';
	$eventsCaption = isset($eventsCaption) && $eventsCaption != '' ? $eventsCaption : 'Events';
	$eventsPosition = isset($eventsPosition) &&  $eventsPosition!= '' ?  $eventsPosition : 'left';
	$class = $eventsPosition == 'left' ? 'float_L' : 'float_R';
	$notificationCaption = 'Admissions';
	$newsCaption = 'Results';
	$eventsCaption = 'Events';
	$height = isset($homePageData) && $homePageData['page'] == 'SHIKSHA_HOME_PAGE' ? 205 : (isset($impDatesPanelHeight) ? $impDatesPanelHeight : 227);
	$rowCount = isset($homePageData) && $homePageData['page'] == 'SHIKSHA_HOME_PAGE' ? 4 : 4;
	if(!(is_array($validateuser) && $validateuser != "false")) { 
        $onRedirect = base64_encode(SHIKSHA_EVENTS_HOME_URL . "/events/Events/showAddEvent#exam");
		$onClick = 'showuserLoginOverLay(this,\'HOMEPAGE_CATEGORY_MIDDLEPANEL_ADDADMISSION\',\'redirect\',\''.$onRedirect .'\');return false;';
	}else {
		if($validateuser['quicksignuser'] == 1) {
	        $base64url = base64_encode($_SERVER['REQUEST_URI']);
	        $onClick = 'javascript:location.replace(\'/user/Userregistration/index/<?php
	echo $base64url?>/1\');return false;';
		} else {  
			$onClick = '';
		}
	}
	
	$clientWidth =  (isset($_COOKIE['client']) && $_COOKIE['client'] != '') ? $_COOKIE['client'] : 1024;
	$characterLength = ($clientWidth < 1000) ? 29 : 40;
	$characterLength = ($categoryData['page'] == '') ? 130 : 250; /* Can be removed */
	$tabMargin = ($clientWidth < 1000) ? 'margin-right:2px;' : '';
	$examNotificationText = 'Add Important Dates' ;
?>
<div>
	<div class="careerOptionPanelBrd">
		<div class="careerOptionPanelHeaderBg">
			<h2><span class="blackFont fontSize_13p">Important Dates</span></h2>
		</div>
		<div class="lineSpace_5">&nbsp;</div>
		<div id="blogTabContainer">
			<div id="navigationTab">
				<ul>
					<li container="notification" tabName="notificationAnnouncements" class="selected" onClick="return selectHomeTab('notification','Announcements');" style="display:none;<?php echo $tabMargin; ?>" fillContainer="updateNotificationsForCatgeoryPages">
						<a href="#" title="<?php echo $notificationCaption;?>" style="text-decoration:none">Admissions</a>
					</li>
					<li container="notification" tabName="notificationExams" class="selected" onClick="return selectHomeTab('notification','Exams');" style="<?php echo $tabMargin; ?>" fillContainer="updateExamNotificationsForCatgeoryPages">
						<a href="#" title="Exams" style="text-decoration:none">Exams</a>						
					</li>
					<li container="notification" tabName="notificationUpdates" class="" onClick="return selectHomeTab('notification','Updates');" style="<?php echo $tabMargin; ?>" fillContainer="updateResultsForCatgeoryPages">
						<a href="#" title="<?php echo $newsCaption;?>" style="text-decoration:none">Results</a>
					</li>
                    <?php
                        if($categoryData['page'] != 'EXAM_DETAIL_PAGE') {
                    ?>
					<li container="notification" tabName="notificationEvents" class="" onClick="return selectHomeTab('notification','Events');" style="<?php echo $tabMargin; ?>" fillContainer="updateEventsForCatgeoryPages">
						<a href="#" title="<?php echo $eventsCaption;?>" style="text-decoration:none">Events</a>
                    </li>
                    <?php
                        }
                    ?>
				</ul>
			</div>
			<div class="clear_L"></div>
		</div>
        <div id="importantDatesContainer">
				<div class="mar_full_10p" style="padding:10px 0px;display:block" id="notificationExamsBlock">
                    <!--<div style="height:<?php //echo $height; ?>px;">-->
					<div>
					<?php
						if(is_array($notifications) && count($notifications['results']) > 0) {
							foreach($notifications['results'] as $notification) {
								$sponsoredResult = '';
								$sponsoredResult = (isset($notification['isSponsored']) && $notification['isSponsored'] == "true") ? '<img src="/public/images/check.gif" style="margin-left:0px" align="absmiddle" />' : '<img src="/public/images/smallBullets.gif" style="margin-left:7px" align="absmiddle" />';
								$sponsoredClass = (isset($notification['isSponsored']) && $notification['isSponsored'] == "true") ? 'quesAnsBulletsSponsored': 'quesAnsBullets';
								$notificationId = isset($notification['id']) ? $notification['id'] : '';
								
								 $notificationTitle = isset($notification['title']) && !empty($notification['title']) ? $notification['title'] :'';
		                        $notificationDisplayTitle = (strlen($notificationTitle) > $characterLength)? substr($notificationTitle,0,$characterLength - 3).'...'  : $notificationTitle;
		                        
								$city = isset($notification['city']) && !empty($notification['city']) ? $notification['city'] : '';
								$location = isset($notification['venue_name']) && !empty($notification['venue_name'])? $notification['venue_name'] .',' : '';
								$startDate = isset($notification['start_date']) && !empty($notification['start_date'])? $notification['start_date'] : '';
								$startDate = date('jS M, y',strtotime( $startDate));
								$datePattern = '/(\d{1,2})(\w{2}) (\w+), (\d+)/i';
								$dateReplacePattern = '${1}<sup>${2}</sup> ${3}, $4';
								$startDate = preg_replace($datePattern, $dateReplacePattern, $startDate);
								$notificationUrl = $notification['url'];
					?>
                    <div class="normaltxt_11p_blk arial">
                        <div style="margin-bottom:2px" class="<?php echo $sponsoredClass; ?>">
	                        <a title="<?php echo $notificationTitle; ?>" href="<?php echo $notificationUrl; ?>" class="fontSize_12p"><?php if(strlen($city)>0) {echo $notificationDisplayTitle .', '. $city; } else{ echo $notificationDisplayTitle; } ?></a>
                        </div>
                        <div style="color:#838487; margin-left:15px;display:none;">
                        <?php echo $city; ?> <?php echo $startDate; ?>
                        </div>
                        <div style="line-height:10px">&nbsp;</div>
                    </div>
					<?php
							}
						} else {
							echo "No exam notifications for this Category - Location combination as of now. Check back soon!";
						}
					?>
                    </div>
                    <div>
						<div class="float_R"><a href="<?php echo SHIKSHA_EVENTS_HOME_URL; ?>/events/Events/showAddEvent#exam" onclick="<?php echo $onClick; ?>" title="Add Important Dates"><?php echo $examNotificationText; ?></a> &nbsp; &nbsp;
						<?php 
							if(count($notifications['results']) > $rowCount && strpos($countryId,',') === false ) {
                                ?><a href="<?php echo SHIKSHA_EVENTS_HOME_URL.'/events/Events/index/1/'.$countryId.'/'.$categoryId; ?>" title="View All">View All</a>
						<?php
							}
						?></div>
					</div>
                </div>
					<div class="clear_L"></div>
				</div>
            	<div class="mar_full_10p verifyDate" style="color:#838487;">Verified Dates</div>
				<div class="lineSpace_10">&nbsp;</div>
				<div class="clear_L"></div>	
	</div>
</div>
