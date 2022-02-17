<a href="javascript:void(0);" id="shortListButtonTop"
	onclick="<?=($courseShortlistedStatus == 1 ? 'return false;' : '')?> gaTrackEventCustom('NATIONAL_COURSE_PAGE', 'shortlist', 'top');  globalShortlistParams = {courseId: <?=$typeId?>, pageType: 'ND_CourseListing', buttonId: this.id, shortlistCallback: 'shortlistCallbackForCoursePages',tracking_keyid: '<?=DESKTOP_NL_LP_COURSE_TOP_SHORTLIST?>'}; checkIfCourseIsAlreadyShortlisted( function() { shikshaUserRegistration.showShortlistRegistrationLayer({courseId: <?=$typeId?>, source: 'ND_CourseListing'})});"
	onmouseout="$j('.shortlist-layer').hide();"
	onmouseover="$j('.shortlist-layer').show();$j('.shortlist-layer').css({'top':$j('.shrtlist-btn').height()+$j('.shrtlist-btn').offset().top+17+'px'});"
	class="shrtlist-btn flLt font-12 <?=$courseShortlistedStatus == 1 ? 'shortlist-disable' : ''?>"
	style="padding: 4px 8px; margin-right: 14px; font-weight: bold; line-height: 19px;"><i
	class="common-sprite shrtlist-star-icon" style="top: -2px !important;"></i><span> <?=($courseShortlistedStatus == 1 ? 'Shortlisted' : 'Shortlist')?> </span></a>
<div class="shortlist-layer" style="top: 40px; display: none">
	<i class="common-sprite shortlist-pointer"
		style="left: 20px; right: auto;"></i>
	<div class="shortlist-widget-head">
		<strong class="tac" style="display: block;">For your shortlisted
			college you can:</strong>
	</div>
	<div class="shortlist-detail-list">
		<ul>
			<li class="flLt">
				<div class="detail-col">
					<i class="common-sprite placmnt-shrtlist-icn"></i> <a href="#">Find
						Placement Data </a>
				</div>
			</li>
			<li class="flRt">
				<div class="detail-col" style="border-right: 0 none;">
					<i class="common-sprite askCurrent-shortlist-icn"></i> <a href="#">Ask
						Current Students </a>
				</div>
			</li>
			<li class="flLt">
				<div class="detail-col last">
					<i class="common-sprite review-shortlist-icn"></i> <a href="#">Read
						Reviews </a>
				</div>
			</li>
			<li class="flRt">
				<div class="detail-col last" style="border-right: 0 none;">
					<i class="common-sprite getAlert-shortlist-icn"></i> <a href="#">Get
						Alerts </a>
				</div>
			</li>
		</ul>
	</div>
	<p class="clear-width tac"
		style="margin: 8px 0 0; color: #d6d0d0; font-size: 14px;">Click on
		shortlist to start</p>
</div>