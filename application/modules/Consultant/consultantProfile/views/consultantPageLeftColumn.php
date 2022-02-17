<?php $this->load->view('consultantPageTitle'); ?>
<div id="left-col">
    <div id="course-dec" class="clearwidth" style="position:relative;">
        <?php $this->load->view('consultantPageNavigationTabs'); ?>
        <div class="tab-details clearfix">
            <?php
                $this->load->view('widgets/consultantOverview');
                if($collegesRepresentedTabFlag)
                {
                    $this->load->view('widgets/collegesRepresented');
                    $this->load->view('widgets/countriesRepresented');
                }
                
                if($servicesOfferedTabFlag)
                {
                    $this->load->view('widgets/servicesOffered');
                }
                
                if($studentAdmittedTabFlag){
                $this->load->view('widgets/studentAdmitted');
                }
            ?>
        </div>
        <?php if($showGutterHelpText) { ?>	
            <div id="courseGutterHelpText" class="navigate-info">
                <i class="common-sprite info-pointer"></i>
                <p>Use this sidebar to find more about consultant details</p>
            </div>
        <?php } ?>
    </div>
    <?php $this->load->view('widgets/consultantPageCallToActions');?>
    <?php $this->load->view('widgets/consultantPhotoWidget'); ?>
</div>