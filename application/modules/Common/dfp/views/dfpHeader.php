<?php $this->load->config('dfp/dfpConfig');
global $dfpConfig;
$pageType = $dfpData['parentPage'];
if(!isset($pageType)){
        $pageType='Others';
}
?>
<div id="headerAdSlot">
<div id="headerAdSlot-1">
<div id="<?php echo $dfpConfig['header'][$pageType]['LB1']['opt_div'];?>" style="height:<?php echo $dfpConfig['header'][$pageType]['LB1']['height'];?>px; width:<?php echo $dfpConfig['header'][$pageType]['LB1']['width'];?>px;">
<script>
googletag.cmd.push(function() { googletag.display("<?php echo $dfpConfig['header'][$pageType]['LB1']['opt_div'];?>"); });
</script>
</div>
</div>
<div id="headerAdSlot-2">
<div id="<?php echo $dfpConfig['header'][$pageType]['LB2']['opt_div'];?>" style="height:<?php echo $dfpConfig['header'][$pageType]['LB2']['height'];?>px; width:<?php echo $dfpConfig['header'][$pageType]['LB2']['width'];?>px;">
<script>
googletag.cmd.push(function() { googletag.display("<?php echo $dfpConfig['header'][$pageType]['LB2']['opt_div'];?>"); });
</script>
</div>
</div>
</div>