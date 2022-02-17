<div id="management-right">
<?php $this->load->view('national/widgets/logoWidget');?>

	<div id="new_course_page_right_column_sticky_top"></div>
	<div id="new_course_page_right_column_sticky" style="width: 275px;">
	   <?php 
	   
	   		if(count($OF_DETAILS)>0) {
				$this->load->view('national/widgets/online_form_right');
			}	
	   ?>
		<div id="new_course_page_right_sticky1" style='display:none;'>
		<?php 	
			if($course->isPaid()) {
				$this->load->view('national/widgets/response_right');
			} else {
				if(in_array("download_e_brochure", $cta_widget_list)) {
					$this->load->view('national/widgets/response_right');
				}
				
				if(in_array("find_best_institute", $cta_widget_list)) {
				?>										
					<img src="/public/images/loader_hpg.gif" align="middle">					
			<?php 	
				}
			}	
        ?>  
		</div>

		<div id="new_course_page_right_sticky2">
		<?php 
			if(in_array("campus_connect", $cta_widget_list)) {
			   //Campus Representative widget: starts
			   $this->load->view('national/widgets/courseCampusRepRight');
			   //Campus Representative widget: starts
			}
		?>
		</div>

		<div id="new_course_page_right_sticky3">
		<?php 
					
			if(in_array("ask_question_widget", $cta_widget_list)) {
				$this->load->view('national/widgets/ask_expert_right');
			}
			
		?>
		</div>

		

		<div class="clearFix"></div>
	</div>

	<div id="new_course_page_right_non_sticky4">
		<?php 		
			if($coursePageCategoryIdentifier == 'MBA_PAGE' && !$campusConnectAvailable) {
				$this->load->view('RecentActivities/ReviewRHSWidget');
			}
		?>
	</div>

	<?php if($coursePageCategoryIdentifier == 'ENGINEERING_PAGE' && isset($rankingSeoUrls) && !empty($rankingSeoUrls)) { ?>
	<div class="browse-top-collge-sec">
    	<p><i class="sprite-bg browse-clg-icon"></i> <strong>Browse Top Colleges</strong></p>
        <ul class="top-colleges-city-list">
        <?php foreach($rankingSeoUrls as $rank) {?>
        	<li><a href="<?=$rank['url']?>"><?=$rank['title']?></a></li>
        <?php } ?>
        </ul>
        <div class="clearFix"></div>
    </div>
    <?php } ?>
	
	<!-- shiksha analytics: starts --> 
	<div class="analytics-cont" id="shikshaAnalyticsForNationalPage">
		
	</div>
	<!-- shiksha analytics: ends -->
	
	<!-- ranking, google ad, fb-widget STARTS-->
	<?php $this->load->view('national/widgets/fbGoogleAd',array("rankingWidgetHTML"=>$rankingWidgetHTML));?>
	<!-- ranking, google ad, fb-widget ENDS-->
</div>
