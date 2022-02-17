<div class="sa_users">
    <?php
    $this->load->view('userProfilePage/profileOverviewSection');
    if($selfProfile === true)
    {
        $this->load->view('userProfilePage/profilePageTabs');
        $this->load->view('userProfilePage/userMailSettings');
    }
    ?>
    <div class="tab-content" <?php echo ($selfProfile === true && $subscrSetting!==""?'style="display:none;"':'') ?>>
        <?php 
        $this->load->view('userProfilePage/profileTabData');
        if($selfProfile === true)
        {
            $this->load->view('userProfilePage/activityTabData');
        }
        ?>
    </div>
</div>