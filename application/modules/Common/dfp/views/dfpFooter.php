<?php global $dfpConfig;?>
<div id="footerAdSlot-d">
        <div id="<?php echo $dfpConfig['footer']['opt_div']?>" style="height:<?php echo $dfpConfig['footer']['height'];?>px; width:<?php echo $dfpConfig['footer']['width'];?>px;">
        <script>
        	if(typeof(googletag) != 'undefined'){
                googletag.cmd.push(function() { googletag.display("<?php echo $dfpConfig['footer']['opt_div']?>"); });
        	}
        </script>
        </div>
</div>

