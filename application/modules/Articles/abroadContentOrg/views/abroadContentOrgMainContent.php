<?php $this->load->view('abroadContentOrgBreadCrumb'); ?>	
<div class="content-wrap clearfix">  
    
    	<!--Content ORG PAGE Starts -->
         
    	<div class="exam-wrap">
	    <?php $this->load->view('abroadContentOrgHeading'); ?>	
            <div class="exam-details-wrap clearwidth">
                <!-- Left Filters Section Start Here -->
                <?php $this->load->view('abroadContentOrgLeftFilter'); ?>
                <!-- Left Filters Section End -->
                
                <!-- Right Hand Section Data Start-->
                <?php $this->load->view('abroadContentOrgRightSection'); ?>
                <!-- Right Hand Section Data End-->
            </div>
        </div>
        
        <!--Exam Content Ends-->
    </div>