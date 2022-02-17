<div class="main">
    <div class="ap-pos">
        <?php
            $this->load->view('applyHomePage/widgets/applyPage/topReviewsWidget');
            // $this->load->view('applyHomePage/widgets/applyPage/topCTAWidget');
        ?>
        <div class="ap-whtBg">
            <input type="hidden" class="applyHomePage" value="<?php echo !$onRMCSuccessFlag?>" />
            <?php if(!is_null($useNewApplyHome)){
                $this->load->view('/studyAbroadCommon/abTrackingFields',array('ABVariate'=>($useNewApplyHome == 1?'new':'old')));
            }
            // $this->load->view('applyHomePage/widgets/applyPage/whyChooseShikshaWidget');
            $this->load->view('applyHomePage/widgets/applyPage/howShikshaCounselingWorkWidget');
            $this->load->view('applyHomePage/widgets/applyPage/shikshaCounselingStats');
            //SA-4366 $this->load->view('applyHomePage/widgets/applyPage/counselorsInfoWidget');
            $this->load->view('applyHomePage/widgets/applyPage/studentSuccessStoryWidget');
            $this->load->view('applyHomePage/widgets/applyPage/shikshaApplyFAQWidget');
            $this->load->view('applyHomePage/widgets/applyPage/stickyCTAWidget');
            $this->load->view('applyHomePage/widgets/applyPage/shikshaApplyDisclaimerWidget');
            ?>
        </div>
    </div>
</div>
