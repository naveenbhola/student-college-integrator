<div id="course-dec" class="clearwidth course-dec" style="position: relative;">    
<?php $this->load->view('listing/abroad/widget/courseLeftNavigationBar'); ?>
<!--        <div class="tab-details clearfix">-->
        <?php $this->load->view('listing/abroad/widget/courseOverview'); ?>
        <?php $this->load->view('listing/abroad/widget/courseEligibility'); ?>
	    <?php $this->load->view('listing/abroad/widget/courseFeeTab'); ?>
	    <?php if($isPlacementFlag){$this->load->view('listing/abroad/widget/placements');} ?>
		<?php if($scholarshipTabFlag){$this->load->view('listing/abroad/widget/courseScholarshipsTab');} ?>
		<?php if(isset($scholarshipCardData) && isset($scholarshipCardData['totalCount']) && $scholarshipCardData['totalCount']>0){$this->load->view('listing/abroad/widget/saScholarshipsTab');} ?>
	    <?php  if($showClassProfileData) $this->load->view('listing/abroad/widget/courseClassProfile'); ?>
	    <?php //if($isMoreInfoTabFlag) {$this->load->view('listing/abroad/widget/courseMoreInfo');} ?>
        <?php if($applicationProcessDataFlag==1){$this->load->view('listing/abroad/widget/courseApplicationProcess');} ?>
		<?php if(!empty($studentGuide)){$this->load->view('listing/abroad/widget/studentGuide');} ?>
	    <?php //if(count($otherCoursesArr['courses'])+count($otherCoursesArr['snapshotCourses']) > 0){$this->load->view('listing/abroad/widget/otherCourses');}?>
	    <?php if( !empty($consultantData) ){ $this->load->view('listing/abroad/widget/consultant'); } ?>
	    
<!--	</div>-->
<?php
if($showGutterHelpText) {
?>	
	<div id="courseGutterHelpText" class="navigate-info">
	    <i class="common-sprite info-pointer"></i>
	    <p>Use this sidebar to find more about course details</p>
	</div>
<?php } ?>
</div>
