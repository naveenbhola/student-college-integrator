<div class="new-container panel-pad admissions-page <?php echo $pageType=='scholarships' ? 'schlrshp-contnr' : ''; ?>">
    
	<?php  $this->load->view("allcontent/widgets/admissionTopSection"); ?>
	<?php if($pageType=="admission" && $instituteObj->getAdmissionDetails() != '' && $coursesData['mostPopularCourse']){ ?>
		<div id="tab-section" class="nav-tabs">
			<?php $this->load->view("allcontent/widgets/admissionNavTabs"); ?>
		</div>
	<?php } ?>
	<?php 
		if($pageType == 'admission'){
			$this->load->view("allcontent/widgets/admissionDetails"); 
         $this->load->view('mcommon5/dfpBannerView',array("bannerPosition" => "X_LAA"));
        $this->load->view('mcommon5/dfpBannerView',array("bannerPosition" => "X_LAA1"));
		}
		else if($pageType == 'scholarships'){
			$this->load->view("allcontent/widgets/scholarshipDetails"); 
			$this->load->view('mcommon5/dfpBannerView',array("bannerPosition" => "X_LAA"));
        $this->load->view('mcommon5/dfpBannerView',array("bannerPosition" => "X_LAA1"));
		}
		if($coursesData['mostPopularCourse']){
			$this->load->view("allcontent/widgets/admissionCourseInfo");
		}
		echo modules::run('mobile_listing5/AllContentPageMobile/getExamsMappedToUniversity',$listing_id, $listing_type);
		echo modules::run('mobile_listing5/AllContentPageMobile/getRelatedLinks',$listing_id,$listing_type,$pageType,$courseIdsMapping);
		$this->load->view("institute/widgets/toolTipLayer"); 

		if($pageType == 'scholarships' && trim($anaWidget['html'])){
			?>
			<div class="ana-ctnr-sclp">
				<?php echo $anaWidget['html']; ?>
				<div id='askProposition'>
			        	<div style='text-align: center; margin-top: 10px; margin-bottom: 10px;'><img border='0' alt='' id='loadingImage1' class='small-loader' style='border-radius:50%;width: 60px;border: 1px solid rgb(229, 230, 230)' src='//<?php echo IMGURL; ?>/public/mobile5/images/ShikshaMobileLoader.gif'/></div>
				</div>
			</div>
			<?php
		}
	?>
</div>