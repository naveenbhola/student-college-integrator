<?php global $dfpConfig;?>
<div id="cookieAdSlot-d">
	<a id='crossButton'>&times;</a>
        <div  id="<?php echo $dfpConfig['CookieBanner']['opt_div']?>" style="height:<?php echo $dfpConfig['CookieBanner']['height'];?>px; width:<?php echo $dfpConfig['CookieBanner']['width'];?>px;">
        <script>
        	if(typeof(googletag) != 'undefined'){
                googletag.cmd.push(function() { googletag.display("<?php echo $dfpConfig['CookieBanner']['opt_div']?>"); });
        	}
        </script>
        </div>
</div>