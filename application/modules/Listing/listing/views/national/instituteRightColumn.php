<div id="management-right">
	<!-- LOGO WIDGET STARTS	-->
	<?php $this->load->view('national/widgets/logoWidget');?>
	<!-- LOGO WIDGET ENDS	-->
	
    <!-- Right column sticky elements Wrapper START -->
    <div id="new_institute_page_right_column_sticky_top"></div>
    <div id="new_institute_page_right_column_sticky" style="width: 275px;">
		    
	<!-- DOWNLOAD EBROCHURE WIDGET STARTS	-->
	<div id="new_institute_page_right_sticky1" style="display:none;">
		    
		<?php
		if(in_array("download_e_brochure", $cta_widget_list)) {
		    $this->load->view('national/widgets/response_right');
		}
		
		if(in_array("find_best_institute", $cta_widget_list)) { ?>
		    <img src="/public/images/loader_hpg.gif" align="middle">					
		<?php }	?>
        </div>
	<!-- DOWNLOAD EBROCHURE WIDGET ENDS	-->

	<!-- CAMPUS REP WIDGET STARTS	-->
	<?php if(in_array("campus_connect", $cta_widget_list)) {
		$this->load->view('national/widgets/instituteCampusRepRight');
	} ?>	
	<!-- CAMPUS REP WIDGET ENDS	-->
	
	<!-- Ask Question Starts -->
	<?php
	if(in_array("ask_question_widget", $cta_widget_list)) {
		$this->load->view('national/widgets/ask_expert_right');
		
	}	?>
	<!-- Ask Question Ends -->


    </div>
<!-- Right column sticky elements Wrapper END -->
	<div>
		
		<?php 		
			if($dominantSubcatData['dominant'] == 23 && !$campusConnectAvailable) {
				$this->load->view('RecentActivities/ReviewRHSWidget');
			}
		?>
	</div>
    
    
	<!-- SHIKSHA ANALYTICS WIDGET STARTS	-->
	<div class="analytics-cont" id="shikshaAnalyticsForInstituteNationalPage">
		    
        </div>
	<!-- SHIKSHA ANALYTICS WIDGET ENDS	-->
	
        <!-- ranking, google ad, fb-widget STARTS-->
	<?php $this->load->view('national/widgets/fbGoogleAd',array("rankingWidgetHTML"=>""));?>
	<!-- ranking, google ad, fb-widget ENDS-->

</div>
