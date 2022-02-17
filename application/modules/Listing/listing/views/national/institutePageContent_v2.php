<?php  if($editedData){ ?>
    <div class="clear-width" style="width:99%; color:#000; background:#fffdc0; padding:8px 5px;">
	<p>This listing was last updated on <?=date('d/m/Y',strtotime($editedData['modifiedDate']))?><?php if($editedData['modifiedBy']) { ?> by <?=$editedData['modifiedBy']?> <?php } ?></p>
    </div>

<?php }
    echo Modules::run('listing/ListingPage/seeAllBranches',$institute,FALSE,"link"); 
    $this->load->view('listing/national/widgets/listingsOverlay');
	if(!empty($course_browse_section_data)) {
		$this->load->view('listing/national/institutePageSelectCourse');
	}
?>

<!--Course Left Col Starts here-->
<div id="management-left">
    
	<!-- TITLE and SEND CONTACT DETAILS START -->
	<?php //$this->load->view('listing/national/titleAndSendContactDetails'); ?>

	<!-- TITLE and SEND CONTACT DETAILS END -->
		
    <!-- PHOTO / VIDEO SLIDER WIDGET STARTS	-->
	<?php $this->load->view('listing/national/InstitutePageHeaderPhotoAndVideo'); ?>
	<!-- PHOTO / VIDEO SLIDER WIDGET ENDS	-->

	<!-- COURSES OFFERED WIDGET STARTS -->
	<?php $this->load->view('national/institute_courses_offered_section'); ?>
	<!-- COURSES OFFERED WIDGET ENDS -->
        
	<!-- Also Viewed INSTITUTES RECOMMENDATION WIDGET FOR FREE COURSES STARTS -->
		<?php
			if(!$paid && in_array("show_institute_recommendations", $cta_widget_list)){ 
			    $this->load->view('national/widgets/alsoViewedInstitutesRecommendations');
			}
		?>
	<!-- Also Viewed INSTITUTES RECOMMENDATION WIDGET FOR FREE COURSES ENDS-->
	
	<!-- WHY JOINED WIDGET STARTS	-->
	<?php $this->load->view('national/institute_why_join_section'); ?>
	<!-- WHY JOINED WIDGET ENDS	-->
	
	
	<!-- CTAS WIDGET STARTS	-->
	<?php	//separate file for belly links widget
		$this->load->view('national/national_institute_left_links_section');
	?>
	
	<!-- Inline Widget for tools to decide MBA college widget only in case of FT MBA starts-->
	<?php if($dominantSubcatData['dominant'] == 23) {
	    $this->load->view('/RecentActivities/InlineWidget');
	}?>
	<!-- Inline Widget for tools to decide ur college widget ends-->
	
	<!-- COURSES OFFERED WIDGET STARTS	-->
	<?php if(!$showAlumniReviewsSection) {
                    $this->load->view('national/courseReviewsOfInstitute');
               }
               else {
        ?>
	<!-- COURSES OFFERED WIDGET ENDS	-->
	
        <!-- ALUMNI SPEAK WIDGET STARTS	-->
	<?php 
                    $this->load->view('national/alumni_reviews_section'); 
               }
        ?>
	<!-- ALUMNI SPEAK WIDGET ENDS	-->
	
	<!-- INSTITUTE (WIKI) DETAILS WIDGET STARTS	-->
	

	<!-- Added by rahul for google crawling-->
	<?php 
		$js_enabled = 1 ;
		$browser = get_browser(null, true);
		if(!empty($browser) && !empty($browser['javascript'])){
			$js_enabled = $browser['javascript'];
		}

	?>

	<?php $this->load->view('national/nationalPageAccordion',array('pageRequestType' =>'institute' , 'js_enabled' => $js_enabled )); ?>
	
	<!-- INSTITUTE (WIKI) DETAILS WIDGET ENDS	-->
	
        <!-- Multilocation layer -->
      
	<?php echo Modules::run('listing/ListingPage/seeAllBranches',$institute,FALSE,"inline"); ?>
        <!-- End Multilocation layer -->
	
    <!-- CONTACT DETAILS WIDGET STARTS	-->
	<?php
		$locations = $institute->getLocations();
		$location = $locations[$currentLocation->getLocationId()];
		if(!empty($location)) {
			if($updated == "true" && !empty($location))
				$this->load->view('listing/national/bottomSendContactDetails');
			else
				$this->load->view('listing/national/bottomSendContactDetailsNotUpdated');
		}
	?>
	<!-- CONTACT DETAILS WIDGET ENDS	-->
	
	
	<!-- DOWNLOAD EBROCHURE WIDGET STARTS	-->
	<div id="institute_page_response_bottom" class="clear-width">
		<?php
		if(in_array("download_e_brochure", $cta_widget_list)) {
		    $this->load->view('national/widgets/response_bottom');
		}
		
		if(in_array("find_best_institute", $cta_widget_list)){
		?>
	    		<div id="national_registration_bottom">
				<img src="/public/images/loader_hpg.gif" align="middle">	
			</div>
		<?php 
	    	}
		?>
	</div>
	<!-- DOWNLOAD EBROCHURE WIDGET ENDS	-->
    <?php if(in_array("ask_question_widget", $cta_widget_list))	{ ?>
		<script>var floatingRegistrationSource = 'LISTING_DETAIL_PAGE_NEW_BOTTOM';</script>
		<?php $this->load->view('national/widgets/ask_question_bottom');    
    }
    ?>
    
		<!-- Also Viewed INSTITUTES RECOMMENDATION WIDGET FOR NON-FREE COURSES STARTS -->
		
			<?php
				if($paid && in_array("show_institute_recommendations", $cta_widget_list)){ 
				    $this->load->view('national/widgets/alsoViewedInstitutesRecommendations');
				}
			?>	
		
		<!-- Also Viewed INSTITUTES RECOMMENDATION WIDGET FOR FREE COURSES ENDS-->
	
		<!-- OTHER INSTITUTES RECOMMENDATION WIDGET STARTS-->
		<?php
			if(in_array("show_institute_recommendations", $cta_widget_list)){
				$this->load->view('national/widgets/otherInstitutesRecommendations');
			}
		?>	
		<!-- OTHER INSTITUTES RECOMMENDATION WIDGET FOR PAID COURSES ENDS-->
    
    

	<!-- Google Ads Start -->
	<?php $this->load->view('national/google_ads_bottom'); ?>
	<!-- Google Ads ENDS -->
</div>
<!--Course Left Col Ends here-->
