<?php if($editedData){ ?>
    <div class="clear-width" style="width:99%; color:#000; background:#fffdc0; padding:8px 5px;">
	<p>This listing was last updated on <?=date('d/m/Y',strtotime($editedData['modifiedDate']))?><?php if($editedData['modifiedBy']) { ?> by <?=$editedData['modifiedBy']?> <?php } ?></p>
    </div>
<?php } ?>

<!--Course Left Col Starts here-->
<div id="management-left">
	<!-- TITLE and SEND CONTACT DETAILS START -->
	<?php $this->load->view('listing/national/titleAndSendContactDetails'); ?>
	<!-- TITLE and SEND CONTACT DETAILS END -->

	<!--	SLIDER WIDGETS START -->
	<?php $this->load->view('listing/national/listingCourseWidgetTop',array('course'=>$course)); ?>

	<!--	SLIDER WIDGETS ENDS -->
	
	<!--	CTAS SECTION STARTS -->
    <?php $this->load->view('national/national_course_left_links_section');?>
	<!--	CTAS SECTION ENDS -->
    
    <!--	NAUKRI DATA SECTION STARTS -->
      <div id="naukri_widget_data">  
            <?php 
      	/*if(is_object($course) && count($course) >0) {     
        	$naukri_inst_id = $course->getInstId();
        	$naukri_course_id = $course->getId();
		echo Modules::run('listing/Naukri_Data_Integration_Controller/getDataForNaukriSalaryWidget',$naukri_inst_id,$naukri_course_id, '', 5 ,5, '','','html');
        }
      */
        ?>
      </div>    
       <!--	NAUKRI DATA SECTION ENDS -->
       
       <!-- Inline Widget for tools to decide ur college widget only in case of FT MBA starts-->
	<?php 
	if($dominantSubcatData['dominant'] == 23) {
		if(in_array($typeId, $IIMCourses)){
			$this->load->view('common/IIMCallPredictorBanner',array('productType'=>'courseListingPage'));
		}
		else{
		    $this->load->view('/RecentActivities/InlineWidget');
		}
	}
	?>
	<!-- Inline Widget for tools to decide ur college widget ends-->
       
       <!-- COURSE REVIEWS START -->
	<?php $this->load->view('listing/national/widgets/courseReviews'); ?>
	<!-- COURSE REVIEWS END -->
        
    <!--  Shortlisting Section : Start-->
    <?php $this->load->view('listing/national/widgets/coursePageShortlistMidWidget');?>  
    <!--  Shortlisting Section : Ends -->
    <!-- OTHER INSTITUTES RECOMMENDATION WIDGET FOR FREE COURSES STARTS-->
    <?php
	if(!$course->isPaid()){ 
	    $this->load->view('national/widgets/alsoViewedInstitutesRecommendations');
	} 
    ?>	
    <!-- OTHER INSTITUTES RECOMMENDATION WIDGET FOR FREE COURSES ENDS-->

	<!--	COURSE DETAILS SECTION STARTS -->
	
	<?php
	    $isEligibilityVisitOnTop = TRUE; //Flags to check conditional wikis in accordion
	    $isAdmissionProcedureOnTop = TRUE;
	    $this->load->view('national/nationalPageAccordion',array('isEligibilityVisitOnTop' => $isEligibilityVisitOnTop,'isAdmissionProcedureOnTop' => $isAdmissionProcedureOnTop));
	?>

	
	
	<!--	COURSE DETAILS SECTION ENDS -->

	<!--	PHOTO - VIDEOS SECTION STARTS -->
	
      <div id="photoVideoForNationalPage" style="clear:both"></div>

	<!--	PHOTO - VIDEOS SECTION ENDS -->

	<!--	CONTACT DETAILS SECTION STARTS -->
	<?php
		$locations = $institute->getLocations();
		$location = $locations[$currentLocation->getLocationId()];
		if(!empty($location)) {
			if($updated == "true")
				$this->load->view('listing/national/bottomSendContactDetails');
			else
				$this->load->view('listing/national/bottomSendContactDetailsNotUpdated');
		}
	?>
	    
	<!--	CONTACT DETAILS SECTION ENDS -->
	

	<?php //if(in_array("campus_connect", $cta_widget_list)) { ?>
	    <!--Campus Connect Changes Start Adding Campus Connect data on course page. Changes done by Rahul -->
	    <?php $this->load->view('listing/national/widgets/campusRep_widget');?>
	    <!--	Campus Connect Changes End -->
	<?php //} ?>
	
    <!--	MULTILOCATION BRANCHES SECTION STARTS -->
             
        <?php echo Modules::run('listing/ListingPage/seeAllBranches',$course,FALSE,"inline"); ?>
    <!--	MULTILOCATION BRANCHES SECTION ENDS -->
	

	<?php if(in_array("ask_question_widget", $cta_widget_list))
	{ ?>
	    <script>var floatingRegistrationSource = 'LISTING_DETAIL_PAGE_NEW_BOTTOM';</script>
   	    <?php $this->load->view('national/widgets/ask_question_bottom');?>
	<?php }?>
	<!--	DOWNLOAD E-BROCHURE SECTION STARTS -->
	<div style="" id="course_page_response_bottom" class="brochure-form-sec clear-width">
	<?php 
	
	    if($course->isPaid()){
	    	echo '<div id="rebCrsPgBtm" class="rebCrsPgBtm"><img src="/public/images/loader_hpg.gif" align="middle"></div>';
			//$this->load->view('national/widgets/response_bottom');
	    } else {
	    	
	    	if(in_array("download_e_brochure", $cta_widget_list)) {
	    		echo '<div id="rebCrsPgBtm" class="rebCrsPgBtm"><img src="/public/images/loader_hpg.gif" align="middle"></div>';
	    		//$this->load->view('national/widgets/response_bottom');		
	    	}
	    	
	    	if(in_array("find_best_institute", $cta_widget_list)) {

		?>
	    		<div id="national_registration_bottom">
				<img src="/public/images/loader_hpg.gif" align="middle">	
			</div>
		<?php 
	    	}	
	    }
		
	?>
	</div>
	<!--	DOWNLOAD E-BROCHURE SECTION ENDS -->		
	
	<!--	OTHER SIMILAR COURSES STARTS HERE-->	    
	
	<?php
		$this->load->view('national/more_courses_from_same_institute');
	?>
	
	<!--	OTHER SIMILAR COURSES ENDS HERE-->
	
	<!-- ONLINE FORM WIDGETS START -->
	<?php
		//check for mba
		if($dominantSubcatData['dominant']  == MBA_SUBCAT_ID)
	   		$this->load->view('national/widgets/onlineFormRecommendations');
	?>	
	<!-- ONLINE FORM WIDGETS ENDS-->
	
	<!-- ALSO VIEWED INSTITUTES RECOMMENDATION WIDGET FOR PAID/FREE COURSES STARTS-->
	<?php
	    if($course->isPaid()){ 
		$this->load->view('national/widgets/alsoViewedInstitutesRecommendations');
	    } 
	?>	
	<!-- ALSO VIEWED INSTITUTES RECOMMENDATION WIDGET FOR PAID/FREE COURSES ENDS-->
	
	<!-- OTHER INSTITUTES RECOMMENDATION WIDGET FOR PAID COURSES STARTS-->
	<?php
	    //if($course->isPaid()){ 
		$this->load->view('national/widgets/otherInstitutesRecommendations');
	    //} 
	?>	
	<!-- OTHER INSTITUTES RECOMMENDATION WIDGET FOR PAID COURSES ENDS-->
	
	<!-- MORE OPTIONS FOR COURSE STARTS-->
	<?php $this->load->view('national/listingCourseMoreOptions'); ?>
	<!-- MORE OPTIONS FOR COURSE STARTS-->
	
	<!-- Google ads bottom starts -->
	<?php $this->load->view('national/google_ads_bottom'); ?>
	<!-- Google ads bottom ends -->
</div>
<!--Course Left Col Ends here-->
