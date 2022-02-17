		 <div class="campus-connect-sec" id = "campus-connect-sec-id" style="clear:both;">
			    <?php 
			    	$js_enabled = 1 ;
			    	$browser = get_browser(null, true);
				    if(!empty($browser) && !empty($browser['javascript'])){
			    		$js_enabled = $browser['javascript'];
			    	}
			    ?>
			    <?php if(in_array("campus_connect", $cta_widget_list)) { ?>
			    <script>
					var caPresent = 'true';
			    </script>
				<?php echo Modules::run('CA/CADiscussions/getCourseTuple',$course->getId(),$institute->getId(),'campusConnect','',3,true); ?>
				<?php } ?>
				<?php echo Modules::run('CA/CADiscussions/getCourseOverviewQnA',$course->getId(),$institute->getId(),$js_enabled,true,false,'courseListingPage','',$replyTrackingPageKeyId);?>
				<?php if(in_array("campus_connect", $cta_widget_list)) { ?>
				<?php echo Modules::run('CA/CADiscussions/getQuestionForm',$course->getId(),$institute->getId(),true,false,'',$questionTrackingPageKeyId); ?>
				<?php } ?>
				<?php echo Modules::run('CA/CADiscussions/getCourseLinks',$course->getId(),true); ?>
		</div>
