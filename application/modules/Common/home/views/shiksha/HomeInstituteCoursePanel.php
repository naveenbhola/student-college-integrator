<?php
	if(!(is_array($validateuser) && $validateuser != "false")) { 
		$onClick = 'showuserOverlay(this,\'add\',1);return false;';
	}else {
		if($validateuser['quicksignuser'] == 1) {
	        $base64url = base64_encode($_SERVER['REQUEST_URI']);
	        $onClick = 'javascript:location.replace(\'/index.php/user/Userregistration/index/<?php
	echo $base64url?>/1\');return false;';
		} else {
			$onClick = '';
		}
	}
	$messageBoardCaption = isset($messageBoardCaption) && $messageBoardCaption != '' ? $messageBoardCaption : 'Ask & Answer';
	$networkCaption = isset($networkCaption) && $networkCaption != '' ? $networkCaption : 'Courses';
$messageBoardCaption = 'Ask & Answer';
	$instituteCoursePosition = isset($instituteCoursePosition) &&  $instituteCoursePosition != '' ?  $instituteCoursePosition: 'right';
	$class = $instituteCoursePosition == 'left' ? 'float_L' : 'float_R';
?>
<div>
	<div class="careerOptionPanelBrd">
		<div class="careerOptionPanelHeaderBg">
			<h4><span class="blackFont fontSize_13p">Most Viewed Institutes &amp; Courses in <?php echo $contentTitle; ?></span></h4>
		</div>
		<div class="lineSpace_5">&nbsp;</div>
		<div id="blogTabContainer">
			<div id="navigationTab">
				<ul>
					<li container="listing" tabName="listingInstitute"  class="selected" onClick="return selectHomeTab('listing','Institute');">
						<a href="#" title="Institutes">Institutes</a>
					</li>
					<li container="listing" tabName="listingCourse" class="" onClick="return selectHomeTab('listing','Course');">
						<a href="#" title="Courses">Courses</a>
					</li>
				</ul>
			</div>
			<div class="clear_L"></div>
		</div>
		<div class="mar_full_10p" style="display:block;<?php echo isset($courseInstitutePanelHeight) ? 'height:'. $courseInstitutePanelHeight .'px;' : ''; ?>; padding-top:10px;" id="listingInstituteBlock">
			<?php $this->load->view('home/shiksha/HomeInstituteWidget',array('truncateStrLength'=>26)); ?>
		</div>
		<div class="mar_full_10p" style="display:none;<?php echo isset($courseInstitutePanelHeight) ? 'height:'. $courseInstitutePanelHeight .'px;' : ''; ?>; padding-top:10px;" id="listingCourseBlock">
			<?php $this->load->view('home/shiksha/HomeCourseWidget'); ?>
		</div>			
		<div class="lineSpace_12">&nbsp;</div>
		<div class="clear_L"></div>
	</div>
</div>
