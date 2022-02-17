<?php ?>
<!-- Exam page content wrapper -->
<div class="content-wrap clearfix">  
    <!--Exam Content Starts -->
    <div class="exam-wrap">
        <!-- START : exam page heading & description -->
        <?php $this->load->view('abroadExamPageHeading'); ?>
        <!-- END : exam page heading & description -->
        
        <!-- START : exam page tile navigation -->
        <?php
        /*
        if($sectionData['sectionId']==1)
        { //show ile navigation only on first section page 
            $this->load->view('abroadExamPageTileNavigation');
        }*/
        ?>
        <!-- END : exam page tile navigation -->
        
        <!-- START : exam page details -->
        <div class="exam-content-wrap clearwidth">
            <!-- START : Left navigation bar -->
            <?php $this->load->view('abroadExamPageLeftNavBar'); ?>
            <!-- END : Left navigation bar -->
            
            <!-- START : right section -->
            <?php $this->load->view('abroadExamPageRightSection'); ?>
            <!-- END : right section -->
        </div>
        <!-- END : exam page details -->
    </div>
    <!--Exam Content Ends -->
</div>
<!-- END : Exam page content wrapper -->