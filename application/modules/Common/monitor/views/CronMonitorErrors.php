<?php $this->load->view('monitor/CronMonitorHeader'); ?>
<div id='cronLagBox' style='margin:20px 20px 10px 20px; color:#888; font-size:12px;'>
    Last Updated: <?php echo date('j M Y H:i:s'); ?>
</div>
<h1 style='margin:0 0 0px 20px; font-weight:normal; color:#444; font-size:24px;'>Today</h1>
<?php
if(is_array($errors['today']) && count($errors['today']) > 0) {
    $this->load->view('monitor/CronMonitorDisplayErrors',array('errorIndex' => 'today'));    
}
else {
?>
    <div id='cronErrorBox' style='background:#f7f7f7; margin:20px 20px 30px 20px; box-shadow: 0 0 5px #ccc; padding:20px; text-align: center; font-size:18px; color:#27ae60'>
        No errors today
    </div>
<?php } ?>

<h1 style='margin:0px 0 0px 20px; padding:0; font-weight:normal; color:#444; font-size:24px;'>Yesterday</h1>
<?php
if(is_array($errors['yesterday']) && count($errors['yesterday']) > 0) {
    $this->load->view('monitor/CronMonitorDisplayErrors',array('errorIndex' => 'yesterday'));    
}
else {
?>
    <div id='cronErrorBox' style='background:#f7f7f7; margin:20px 20px 30px 20px; box-shadow: 0 0 5px #ccc; padding:20px; text-align: center; font-size:18px; color:#27ae60'>
        No errors yesterday
    </div>
<?php } ?>

<h1 style='margin:0px 0 0px 20px; font-weight:normal; color:#444; font-size:24px;'>Previous</h1>
<?php
if(is_array($errors['previous']) && count($errors['previous']) > 0) {
    $this->load->view('monitor/CronMonitorDisplayErrors',array('errorIndex' => 'previous'));    
}
else {
?>
    <div id='cronErrorBox' style='background:#f7f7f7; margin:20px 20px 50px 20px; box-shadow: 0 0 5px #ccc; padding:20px; text-align: center; font-size:18px; color:#27ae60'>
        No previous errors
    </div>
<?php } ?>

<script>
    $j(document).ready(function() {
        setTimeout(function() {
            window.location.reload();
        },900000);
    });
</script>

<?php $this->load->view('monitor/CronMonitorFooter'); ?>
