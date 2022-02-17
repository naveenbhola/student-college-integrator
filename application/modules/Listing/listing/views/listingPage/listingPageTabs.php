<div id="page-tabs">
        <?php 
		if(strpos($overviewTabUrl,"?") === FALSE) {
			$cpgs_append  = '?cpgs='.$course_page_required_category;
		} else if(strpos($overviewTabUrl,"?") !==FALSE) {                     
				$cpgs_append  = '&cpgs='.$course_page_required_category;                   
		}
       ?>
	<ul>
		<li title="Overview" <?php echo ($tab == 'overview')?'class="active"':''; ?>><a id="overviewTabLink" href="<?=$overviewTabUrl.$cpgs_append?>#page-header">Overview</a></li>
		<?php
		if(isset($campusRepTabUrl) && $campusRepTabUrl!=''){ ?>
		<li title="Here's where you talk to current students/alumni/staff from the institute." <?php echo ($tab == 'campusRep')?'class="active"':''; ?>><a id="anaTabLink" href="<?=$campusRepTabUrl.$cpgs_append?>#page-header">Current Student</a></li>
		<?php
		}else{ ?>
		<li title="Here's where you talk to current students/alumni/staff from the institute." <?php echo ($tab == 'ana')?'class="active"':''; ?>><a id="anaTabLink" href="<?=$askNAnswerTabUrl.$cpgs_append?>#page-header">Current Student</a></li>
		<?php
		}
		?>

		<?php
			if((count($institute->getCourses())) > 3){
		?>
		<li title="Courses" <?php echo ($tab == 'courses')?'class="active"':''; ?>><a id="coursesTabLink" href="<?=$courseTabUrl.$cpgs_append?>#page-header">Courses</a></li>
		<?php
			}
		?>
		<?php
			$photos = $institute->getPhotos();
			$videos = $institute->getVideos();                        
			if((count($photos) + count($videos)) > 0){
		?>
		<li title="Photos & Videos" <?php echo ($tab == 'media')?'class="active"':''; ?>><a id="photosTabLink" href="<?=$mediaTabUrl.$cpgs_append?>#page-header">Photos &amp; Videos</a></li>
		<?php
			}
                        
                        // if($institute->getAlumniRating() && $institute->getAlumniRating()>0){
                        if(isset($alumniFeedbackRatingCount) && $alumniFeedbackRatingCount > 0){
		?>
		<li title="Alumni Speak" <?php echo ($tab == 'alumni')?'class="active"':''; ?>><a id="alumniTabLink" href="<?=$alumniTabUrl.$cpgs_append?>#page-header">Alumni Speak</a></li>
		<?php
			}
		?>
		<?php
			if($apply && $paid && $tab != 'courses' && $tab != "ana" && $tab != 'campusRep'){
		?>
		<li title="Apply Now" class="applyTab" style="font-size:12px !important">
			<div onclick="hideFloatingRegistration(); $j('body,html').animate({scrollTop:$j('#bottomWidget').offset().top - 70},500);">Apply Now <span class="btn-arrow"></span></div>
		</li>
		<?php
			}else{	
		?>
		<!--li class="onlineTab" id="onlineFormLink">
		</li-->
		<?php
			}
		?>
	</ul>
<?php
	/*$updatedTime =array();
	$lastUpdatedDate = ($pageType == 'institute') ? $institute->getLastUpdatedDate() : $course->getLastUpdatedDate();
	$updatedTime = explode(" ",$lastUpdatedDate);
	$updatedDate = explode("-",$updatedTime[0]);
	$updatedDateReadableFormat = array($updatedDate[2],$updatedDate[1],$updatedDate[0]);
	$updatedDate = implode("/",$updatedDateReadableFormat);
	if(trim($updatedDate,"/")!=""){ ?> <div id = "last_updated"> <?php echo "This information  was last updated on ".$updatedDate;?></div> <?php } */
?>
<div class="onlineTab" id="onlineFormLink"></div>
</div>
