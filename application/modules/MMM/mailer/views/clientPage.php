<?php
$headerComponents = array(
    'css'   => array('headerCms','raised_all','mainStyle','footer','mailScheduler'),
    'js'    => array('mailer','mailCampaign','CalendarPopup')
);
    $this->load->view('enterprise/headerCMS', $headerComponents);
    $this->load->view('common/calendardiv');
?>

<div class="mar_full_10p">
    <div>
    <?php $this->load->view('mailer/left-panel-top'); ?>
        
        <div id="dataLoaderPanel" style="position:absolute;display:none">
    <img src="/public/images/loader.gif"/>
    </div>


    <?php if ($resendMailer !=1 && $isEdit != true && $isReplicate != 1) {
        $this->load->view('mailer/clientLoginView');}
    else if( $resendMailer ==1){ 
        $this->load->view('mailer/MailSchedulerWidgets/resendMailer');
    } ?>


</div>
    <div class="clearFix"></div>
    <div class="lineSpace_35">&nbsp;</div>
</div>
<div class="clearFix"></div>
    <br />
    <br />
</body>
</html>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script>$j = $.noConflict();</script>


