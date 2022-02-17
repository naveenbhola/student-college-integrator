<?php
	$announcementObj = $universityObj->getAnnouncement();
	if($announcementObj) {
		$announcementText = $announcementObj->getAnnouncementText();
		$announcementActionText = $announcementObj->getAnnouncementActionText();
		$announcementStartDate = $announcementObj->getAnnouncementStartDate();
		$announcementEndDate = $announcementObj->getAnnouncementEndDate();
		$today = date("Y-m-d");
		if($announcementText && $today >= $announcementStartDate && $today <= $announcementEndDate) { ?>
			<div class="update-sec clearwidth">
				<h2><strong>Announcement</strong></h2>
				<div class="update-info clearwidth">
					<div class="update-content">
						<i class="common-sprite update-arr"></i>
						<?=  htmlentities($announcementText)?>
					</div>
					<div class="update-reg-info">
						<?=$announcementActionText?>
					</div>
				</div>
			</div>
<?php 	}
	} ?>
