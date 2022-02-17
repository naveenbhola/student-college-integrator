<div class="tab_wrapper">
    <?php if($selfProfile === true){ 
        $this->load->view('userProfileSA/userSettingTabs'); 
        $this->load->view('userProfileSA/userMailSettings'); 
    } ?>
    <div class="tab-content" <?php echo ($selfProfile===true && $subscrSetting!==""?'style="display:none;"':'') ?>>
        <div class="tabs_data" id="tab1">
            <?php
            $this->load->view('userProfileSA/userEducationDetails');
            if($selfProfile === true){
	            $this->load->view('userProfileSA/userPersonalDetails');
            }
            ?>
        </div>
    </div>
</div>