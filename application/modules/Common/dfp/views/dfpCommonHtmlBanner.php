<?php 
$this->load->config('dfp/dfpConfig');
global $dfpConfig;
if(!empty($dfpData)) { 
?>
<div class="new-row adSlot">
<div id="<?php echo $dfpConfig[$bannerType][$dfpData['parentPage']][$bannerPlace]['opt_div'];?>" style="height:<?php echo $dfpConfig[$bannerType][$dfpData['parentPage']][$bannerPlace]['height'];?>px; width:<?php echo $dfpConfig[$bannerType][$dfpData['parentPage']][$bannerPlace]['width'];?>px;">
<script>
googletag.cmd.push(function() { googletag.display("<?php echo $dfpConfig[$bannerType][$dfpData['parentPage']][$bannerPlace]['opt_div']?>"); });
</script>
</div>
</div>
<?php } ?>
