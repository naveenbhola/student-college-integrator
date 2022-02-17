<?php
     $jumpToLinksCount = count(array_keys($jumpMenuData, 1));
     // Show the jump to navigation bar only if we have 3 or more links
     if( $jumpToLinksCount >= 3 )
     {
	// show the jump to links of course page
	if( $pageType == 'course' )
	{
?>

<div id="CP_jumpto_vertical" class="sticky-nav verticle-nav" style='position:fixed;left:2.9%;display:none;'>
	<ul>
		<li style='border:0;'><span class="jump-title">Jump to</span></li>
		<li class = "important-info-jump"><a href="#" uniqueattr="LISTING_COURSE_PAGES/JUMP_TO_IMP_INFO" elementtofocus="insitute-criteria-box">Important Info</a></li>
		<?php
		 if(!empty($jumpMenuData['ALUMNI_EMPLOYMENT_STATS']))
		{ ?>
		<li id="alumni_side_widget" style="display:none;"><a href="#" uniqueattr="LISTING_COURSE_PAGES/JUMP_TO_ALUMNI_EMPLOYMENT_STATS" elementtofocus="alumni-data-widget">Alumni Employment Stats</a></li>
		<?php }
		 if(!empty($jumpMenuData['COURSE_REVIEWS']))
		{ ?>
		<li><a href="#" uniqueattr="LISTING_COURSE_PAGES/JUMP_TO_COURSE_REVIEWS" elementtofocus="course-reviews-section">College Reviews</a></li>
		<?php }
		 if(!empty($jumpMenuData['COURSE_DETAILS']))
		{ ?>
		<li class="active"><a href="#" uniqueattr="LISTING_COURSE_PAGES/JUMP_TO_COURSE_DETAILS" elementtofocus="course-details-section">Course Details</a></li>
		<?php }
		 if(!empty($jumpMenuData['PHOTOS_VIDEOS']))
		{ ?>
		<li><a href="#" uniqueattr="LISTING_COURSE_PAGES/JUMP_TO_PHOTOS_VIDEOS" elementtofocus="photos-vids-sec">Photos & Videos</a></li>
		<?php }
		 if(!empty($jumpMenuData['CAMPUS_REP']))
		{ ?>
		<li><a href="#" uniqueattr="LISTING_COURSE_PAGES/JUMP_TO_CAMPUS_REP" elementtofocus="campus-connect-sec">Current Students</a></li>
		<?php }
		if(!empty($jumpMenuData['DOWNLOAD_EBROCHURE']))
		{ ?>
		<li><a href="#" uniqueattr="LISTING_COURSE_PAGES/JUMP_TO_DOWNLOAD_BROCHURE" elementtofocus="brochure-form-sec">Download E-Brochure</a></li>
		<?php }
		if(!empty($jumpMenuData['SIMILAR_COURSES']))
		{ ?>
		<li><a href="#" uniqueattr="LISTING_COURSE_PAGES/JUMP_TO_SIMILAR_COURSES" elementtofocus="similar-courses-section">Similar Courses</a></li>
		<?php }
		 ?>
    </ul>
</div>

<div id="CP_jumpto_horizontal" class="sticky-nav horizontal-nav" style="position:fixed;top:0;z-index:100;display:none;">
	<ul>
    	<li class="jump-item"><span class="jump-title">JUMP TO</span></li>
                <li class = "important-info-jump"><a href="#" uniqueattr="LISTING_COURSE_PAGES/JUMP_TO_IMP_INFO" elementtofocus="insitute-criteria-box">Important Info</a></li>
		<?php
		  if(!empty($jumpMenuData['COURSE_REVIEWS']))
		{ ?>
		<li><a href="#" uniqueattr="LISTING_COURSE_PAGES/JUMP_TO_COURSE_REVIEWS" elementtofocus="course-reviews-section">College Reviews</a></li>
		<?php }
		 if(!empty($jumpMenuData['COURSE_DETAILS']))
		{ ?>
		<li class="active"><a href="#" uniqueattr="LISTING_COURSE_PAGES/JUMP_TO_COURSE_DETAILS" elementtofocus="course-details-section">Course Details</a></li>
		<?php }
		 if(!empty($jumpMenuData['PHOTOS_VIDEOS']))
		{ ?>
		<li><a href="#" uniqueattr="LISTING_COURSE_PAGES/JUMP_TO_PHOTOS_VIDEOS" elementtofocus="photos-vids-sec">Photos & Videos</a></li>
		<?php }
		 if(!empty($jumpMenuData['CAMPUS_REP']))
		{ ?>
		<li><a href="#" uniqueattr="LISTING_COURSE_PAGES/JUMP_TO_CAMPUS_REP" elementtofocus="campus-connect-sec">Campus Repesentative</a></li>
		<?php }
		 if(!empty($jumpMenuData['DOWNLOAD_EBROCHURE']))
		{ ?>
		<li><a href="#anchorDownloadEBrochure" uniqueattr="LISTING_COURSE_PAGES/JUMP_TO_DOWNLOAD_BROCHURE" elementtofocus="brochure-form-sec">Download E-Brochure</a></li>
		<?php }
		 if(!empty($jumpMenuData['SIMILAR_COURSES']))
		{ ?>
		<li><a href="#" uniqueattr="LISTING_COURSE_PAGES/JUMP_TO_SIMILAR_COURSES" elementtofocus="similar-courses-section">Similar Courses</a></li>
		<?php }
		 ?>
    </ul>
</div>

<?php
	}
     }
	// show the jump to links of institute page
	if( $pageType == 'institute' )
	{
		
?>
<div id="IP_jumpto_vertical" class="sticky-nav verticle-nav" style='position:fixed;left:2.9%;display:none;'>
	<ul>

		<li style='border:0;'><span class="jump-title">Jump to</span></li>
		<li style='display:none;'><a href="#" uniqueattr="LISTING_INSTITUTE_PAGES/JUMP_TO_PHOTOS_VIDEOS" elementtofocus="photos-videos-sec">Photos & Videos</a></li>
		
		<li style='display:none;'><a href="#" uniqueattr="LISTING_INSTITUTE_PAGES/JUMP_TO_ALL_COURSES" elementtofocus="all-courses-sec">Courses Offered</a></li>

		<?php
		 if(!empty($jumpMenuData['WHY_JOIN']))
		{ ?>
		<li style='display:none;'><a href="#" uniqueattr="LISTING_INSTITUTE_PAGES/JUMP_TO_WHY_JOIN" elementtofocus="why-join-sec">Why Join</a></li>
		<?php }
		 if(!empty($jumpMenuData['COURSE_REVIEWS']))
		{ ?>
		<li><a href="#" uniqueattr="LISTING_INSTITUTE_PAGES/JUMP_TO_COURSE_REVIEWS" elementtofocus="course-reviews-section">College Reviews</a></li>
		<?php }
		 if(!empty($jumpMenuData['ALUMNI_SPEAK']))
		{ ?>
		<li style='display:none;'><a href="#" uniqueattr="LISTING_INSTITUTE_PAGES/JUMP_TO_ALUMINI_SPEAK" elementtofocus="alumini-speak-sec">Reviews</a></li>
		<?php }
		if(!empty($jumpMenuData['INSTITUTE_DETAILS']))
		{ ?>
		<li style='display:none;' class="active"><a href="#" uniqueattr="LISTING_INSTITUTE_PAGES/JUMP_TO_INSTITUTE_DETAILS" elementtofocus="institute-details-sec">College Details</a></li>
		<?php }
		 if(!empty($jumpMenuData['DOWNLOAD_EBROCHURE']))
		{ ?>
		<li style='display:none;'><a href="#" uniqueattr="LISTING_INSTITUTE_PAGES/JUMP_TO_DOWNLOAD_BROCHURE" elementtofocus="brochure-form-sec">Download E-Brochure</a></li>
		<?php }
		 ?>
    </ul>
</div>

<div id="IP_jumpto_horizontal" class="sticky-nav horizontal-nav" style="position:fixed;top:0;z-index:100;display:none;">
	<ul>
		  <li class="jump-item"><span class="jump-title">JUMP TO</span></li>

		  <li style='display:none;'><a href="#" uniqueattr="LISTING_INSTITUTE_PAGES/JUMP_TO_PHOTOS_VIDEOS" elementtofocus="photos-videos-sec">Photos & Videos</a></li>

		  <li style='display:none;'><a href="#" uniqueattr="LISTING_INSTITUTE_PAGES/JUMP_TO_ALL_COURSES" elementtofocus="all-courses-sec">Courses Offered</a></li>

		<?php
		 if(!empty($jumpMenuData['WHY_JOIN']))
		{ ?>
		<li style='display:none;'><a href="#" uniqueattr="LISTING_INSTITUTE_PAGES/JUMP_TO_WHY_JOIN" elementtofocus="why-join-sec">Why Join</a></li>
		<?php }
		 if(!empty($jumpMenuData['COURSE_REVIEWS']))
		{ ?>
		<li><a href="#" uniqueattr="LISTING_INSTITUTE_PAGES/JUMP_TO_COURSE_REVIEWS" elementtofocus="course-reviews-section">College Reviews</a></li>
		<?php }
		 if(!empty($jumpMenuData['ALUMNI_SPEAK']))
		{ ?>
		<li style='display:none;'><a href="#" uniqueattr="LISTING_INSTITUTE_PAGES/JUMP_TO_ALUMINI_SPEAK" elementtofocus="alumini-speak-sec">Reviews</a></li>
		<?php }
		if(!empty($jumpMenuData['INSTITUTE_DETAILS']))
		{ ?>
		<li style='display:none;' class="active"><a href="#" uniqueattr="LISTING_INSTITUTE_PAGES/JUMP_TO_INSTITUTE_DETAILS" elementtofocus="institute-details-sec">College Details</a></li>
		<?php }
		 if(!empty($jumpMenuData['DOWNLOAD_EBROCHURE']))
		{ ?>
		<li style='display:none;'><a href="#" uniqueattr="LISTING_INSTITUTE_PAGES/JUMP_TO_DOWNLOAD_BROCHURE" elementtofocus="brochure-form-sec">Download E-Brochure</a></li>
		<?php }
		 ?>    </ul>
</div>

<?php
	}
?>
