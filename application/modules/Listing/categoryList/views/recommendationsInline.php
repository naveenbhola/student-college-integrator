<?php
    if($recommendationsExist) {
?>

<div class="recommended-section req-bro-box"> 
    <i class="pointer"></i>
    <div class="recommend-head lyaer-head" style="background:none; padding:0"> 
        <h2 class="flLt">Students who showed interest in this institute also looked at:</h2>
        <div class="next-rev">
            <a href="javascript:void(0)" class="cl-icon"></a>
        </div>
        <div class="clearFix"></div>
    </div>
    <div class="recommend-items" style="width: 620px; overflow: hidden;">
        <ul id="slideContainer" style="width:2070px; position: relative; left:0px;">
	    <?php
		foreach($alsoViewedInstitutes as $institute) {
		    $course = $institute->getFlagshipCourse();
		    $instituteId = $institute->getId();
		    $courseId = $course->getId();
		    $instituteFullName = $institute->getName();
		    $courseFullName = $course->getName();
		    $instituteName = strlen($instituteFullName) > 40 ? substr($instituteFullName, 0, 40).'...' : $instituteFullName;
		    $courseName = strlen($courseFullName) > 45 ? substr($courseFullName, 0, 45).'...' : $courseFullName;
		    $instituteHeaderImage = $institute->getMainHeaderImage();
		    $instituteThumbURL = $instituteHeaderImage->getThumbURL();
		    $courseURL = $course->getURL();
		    $instituteURL = $institute->getURL();
		    $mainLocation = $course->getMainLocation();
		    $mainLocationId = $mainLocation->getLocationId();
		    $mainCity = $mainLocation->getCity();
		    $mainCityName = $mainCity->getName();
		    $mainCityId = $mainCity->getId();
		    $exams = $course->getEligibilityExams();
		    $cutoff = '';
		    foreach($exams as $exam) {
			$examName = $exam->getAcronym();
			$marks = $exam->getMarks();
			if($marks != 0) {
			    $cutoff .= $examName.' ('.$marks.'), ';
			}
		    }
		    $cutoff = substr($cutoff, 0, -2);
		    $fees = $course->getFees($mainLocationId)->__toString();
		    $ranking = $course->getRanking()->__toString();
		    $duration = $course->getDuration()->__toString();
		    $mode = $course->getCourseType();
	    ?>
	    <li onmouseover="highlightRequestButton(<?php echo $instituteId; ?>);" onmouseout="removeRequestButtonHighlight(<?php echo $instituteId; ?>);">
		<div class="inst-height">
		    <div class="inst-pic">
			<?php
			    if($instituteHeaderImage && $instituteThumbURL) {
				echo '<a href="'.$instituteURL.'" rel="nofollow"><img src="'.$instituteThumbURL.'" width="118" alt="'.html_escape($instituteFullName).'" title="'.html_escape($instituteFullName).'" onmouseover="underlineCourseLink('.$instituteId.');" onmouseout="removeCourseLinkUnderline('.$instituteId.');"/></a>';
			    }
			    else {
				echo '<a href="'.$instituteURL.'" rel="nofollow"><img src="/public/images/avatar.gif" alt="'.html_escape($instituteFullName).'" title="'.html_escape($instituteFullName).'" onmouseover="underlineCourseLink('.$instituteId.');" onmouseout="removeCourseLinkUnderline('.$instituteId.');"/></a>';
			    }
			?>
		    </div>
		    <p class="inst-name" title="<?php echo $instituteFullName; ?>"><a href="<?php echo $instituteURL; ?>" style="color: inherit;"><?php echo $instituteName ?></a></p>
		    <p><strong><?php echo $mainCityName; ?></strong></p>
		    <p><a id="courseLink_<?php echo $instituteId; ?>" href="<?php echo $courseURL; ?>" title="<?php echo $courseFullName; ?>" onmouseover="underlineCourseLink(<?php echo $instituteId; ?>);" onmouseout="removeCourseLinkUnderline(<?php echo $instituteId; ?>);"><?php echo $courseName ?></a>
		    </p>
		    <?php
			if(!empty($cutoff)) {
			    echo '<p><strong>Eligibility</strong>: '.$cutoff.'</p>';
			}
			else if(!empty($fees)) {
			    echo '<p><strong>Fees</strong>: '.$fees.'</p>';
			}
			else if(!empty($ranking)) {
			    echo '<p><strong>Ranking</strong>: '.$ranking.'</p>';
			}
			else if(!empty($duration)) {
			    echo '<p><strong>Duration</strong>: '.$duration.'</p>';
			}
			else if(!empty($mode)) {
			    echo '<p><strong>Mode</strong>: '.$mode.'</p>';
			}
		    ?>
		</div>
		<?php
		    if($course->isPaid()) {
		?>
		<a id="apply_button<?php echo $instituteId; ?>" href="javascript:void(0)" class="<?php if(in_array($courseId,$recommendationsApplied)) echo 'white-disabled white-button'; else echo 'white-button'; ?>" onClick = "<?php if(in_array($courseId,$recommendationsApplied)) echo 'return false;'; else echo 'doAjaxApply('.$instituteId.', '.$courseId.', '.$mainCityId.', '.$mainLocationId.', '.$mainCityId.', \'CP_Reco_popupLayer\');'; ?>">Request E-brochure</a>
		<div id="apply_confirmation<?php echo $instituteId; ?>" class="recom-aply-row" <?php if(in_array($courseId,$recommendationsApplied)) echo "style='display:block;'"; ?>>
		    <i class="thnx-icon"></i>
		    E-brochure successfully mailed.
		</div>
		<input type="hidden" id="apply_status<?php echo $instituteId; ?>" value="<?php if(in_array($courseId,$recommendationsApplied)) echo '1'; else echo '0'; ?>"/>
		<input type="hidden" id="params<?php echo $instituteId; ?>" value="<?php echo html_escape(getParametersForApply($userInfo,$course,$mainCityId,$mainLocationId)); ?>"/>
		<?php
		    }
		?>
	    </li>
	    <?php
		}
	    ?>
        </ul>
    </div>
    <div class="layer-bot clear-width">
        <div class="slide-bullets">
            <ul>
                <li class="active"></li>
                <li></li>
                <li></li>
            </ul>
        </div>
        <div class="next-rev">
            <a class="prev-icon" href="javascript:void(0)" onclick="slideLeft();"></a>
            <a class="next-icon-active" href="javascript:void(0)" onclick="slideRight();"></a>  
        </div>
        <div class="clearFix"></div>
    </div>
</div>
<?php
    }
?>

<script>
var slideWidth = 620;
var numSlides = <?php echo ceil($numberOfRecommendations / 3); ?>;
var currentSlide = 0;
var firstSlide = 0;
var lastSlide = (numSlides - 1) * (-1);

if (numSlides == 1) {
    $('prev').className = 'prev-icon';
    $('next').className = 'next-icon';
}


</script>