<?php
$widget = "campusRepInsttLayer";
?>

<div class="layer-head">
	<a href="#" class="flRt sprite-bg cross-icon" onclick="hideListingsOverlay(true); return false;" title="Close"></a>
	<div class="layer-title-txt">Ask your queries to current students of this college</div>
</div>
<ul class="form-list">
<li>
	Please select the desired course
</li>

<li>
	<select class="universal-select" style="width:300px" caption="course" id="course_<?=$widget?>" name="course_<?=$widget?>" onchange="national_listings_obj.validateDropDown(this);" onblur="national_listings_obj.validateDropDown(this); return false;" required="true">
		<option selected value="">Desired course</option>
		<?php  foreach($campusConnectCourses as $course)
		{
			echo '<option customurl = "'.$course->getURL().'" value="'.$course->getId().'">'.html_escape($course->getName()).'</option>';
		} ?>
	</select>
	<div class="clearFix"></div>
	<div style="display:none">
		<div class="errorMsg" id="course_<?=$widget?>_error" style="padding-left:3px; clear:both;"></div>
	</div>
</li>

<li style="margin-bottom:8px">
	<a href="javascript:void(0);" class="orange-button" uniqueattr="LISTING_INSTITUTE_PAGES/campusRepInsttLayer" onclick="goToCourseURL('course_<?=$widget?>');">Proceed</a>
</li>
</ul>
