<?php $this->load->view('monitor/CronMonitorHeader'); ?>
<div id='cronLagBox' style='margin:20px 20px 10px 20px; color:#888; font-size:12px;'>
    Last Updated: <?php echo date('j M Y H:i:s'); ?>
</div>
<div id='cronLagBox' style='background:#f7f7f7; margin:0 20px 30px 20px; box-shadow: 0 0 5px #ccc; padding:30px 20px;'>
    <div style='margin:0 auto; width:800px;'>
    <table width='800' cellpadding='0' cellspacing='0'>
        <?php foreach($cronsForLagMonitoring as $cronId => $cron) { ?>
        <tr>
            <td width='<?php if($_REQUEST['onMonitor'] == 1) echo "250"; else echo "600"; ?>' class='tdleft' style='border-right:none; font-size:15px; color:#444;'><?php echo $cron['name']; ?></td>
            <td class='tdright' style='border-right:none; font-size:15px;' id='cronLag_<?php echo $cronId; ?>'><img src='/public/images/ldb_ajax-loader.gif' /></td>
        </tr>
        <?php } ?>
    </table>
    </div>
</div>

<script>
$j(document).ready(function() {
	refreshCronStatus();
	setTimeout(function() { window.location.reload(); } ,1800000);
});
function refreshCronStatus() 
{
	<?php foreach($cronsForLagMonitoring as $cronId => $cron) { ?>
	$j('#cronLag_<?php echo $cronId; ?>').html("<img src='/public/images/ldb_ajax-loader.gif' />");
        $j.post('/monitor/CronMonitor/getLag/<?php echo $cronId; ?>',{},function(data) {
            $j('#cronLag_<?php echo $cronId; ?>').html(data);
            var lagLimit = <?php echo $cron['lagLimit']; ?>;
            if (data > lagLimit) {
                $j('#cronLag_<?php echo $cronId; ?>').css('background','#e74c3c');
                $j('#cronLag_<?php echo $cronId; ?>').css('color','#eee');
            }
        })
    <?php } ?>	
}
</script>
<?php $this->load->view('monitor/CronMonitorFooter'); ?>
