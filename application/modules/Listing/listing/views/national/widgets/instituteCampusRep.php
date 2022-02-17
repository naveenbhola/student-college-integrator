<?php
$widget = "campusRepRightInstt";
$comments = $campusRepInstituteCommentData;
?>

<div class="talk-widget">
	<p><i class="sprite-bg talk-icon2"></i><b>Ask your queries to current students of this college</b></p>
	
	<div class="talk-widget-content">
		
		<ul>
			<?php foreach($caData as $index => $ca): ?>
				<?php if(!empty($ca['imageURL'])):?>
				<li><img src="<?php echo substr_replace($ca['imageURL'],'_s.',strrpos($ca['imageURL'],'.' ),1);?>" alt="image1" /></li>
				<?php else :?>
				<li><img src="/public/images/photoNotAvailable.gif" alt="image1" /></li>
				<?php endif; ?>
			<?php endforeach; ?>
		</ul>
		
		
		<div style="margin-top:10px">
			<select class="universal-select" caption="course" id="campus_rep_course_<?=$widget?>" name="campus_rep_course_<?=$widget?>" onchange="national_listings_obj.validateDropDown(this);" onblur="national_listings_obj.validateDropDown(this); return false;" required="true">
				<option selected value="">Please Select course</option>
				<?php  foreach($campusConnectCourses as $course)
				{
					echo '<option customurl = "'.$course->getURL().'" value="'.$course->getId().'">'.html_escape($course->getName()).'</option>';
				} ?>
			</select>
			<div class="clearFix"></div>
			<div style="display:none">
				<div class="errorMsg" id="campus_rep_course_<?=$widget?>_error" style="padding-left:3px; clear:both;"></div>
			</div>
		</div>
		
		
		<div class="talk-comment">
			<p class="flLt" style="display: none" id="comments"><?=$comments['commentCount']?><?php echo ($comments['commentCount'] == 1) ? " comment" : " comments"; ?> </p>
			<a class="manage-contact-btn flRt participate-btn" uniqueattr="LISTING_INSTITUTE_PAGES/CampusRepInsttRightPanel" href="javascript:void(0);" onclick="goToCourseURL('campus_rep_course_<?=$widget?>')">Ask Now</a>
			<div class="clearFix"></div>
		</div>
		
	</div>
</div>
<script>
	commentsShowHide(<?=$comments['commentCount']?>);
	
	function commentsShowHide(numberOfComments) {
		if (numberOfComments > 0) {
			document.getElementById('comments').style.display = "block";
		}
	}
	
    function goToCourseURL(id) {
		var obj = $j("#" + id);
		if(typeof id != "undefined") {
			if ($j("#" + id + " option:selected").val() == "") {
				national_listings_obj.validateDropDown(obj[0]);
			}
			else {
				var url = $j("#"+ id + " option:selected").attr("customurl");
				url = url + "#ca_aqf";
				window.location.href = url;
			}
		}
    }
</script>
