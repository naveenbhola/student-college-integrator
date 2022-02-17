<?php global $rtr_module;
global $rtr_class;
global $rtr_method;
if(empty($rtr_module))
	$rtr_module = '';
if(empty($rtr_class))
	$rtr_class = '';
if(empty($rtr_method))
	$rtr_method = '';
?>
<script> window.onerror = function (msg, url, line, col, exception) { var img = new Image(); var srcUrl = '/AppMonitor/logJSErrorsData/logJSerrors?msg=' + encodeURIComponent(msg) +'&line=' + encodeURIComponent(line) +'&url=' + encodeURIComponent(url) +'&col=' + encodeURIComponent(col) +'&exception='+encodeURIComponent(exception)+'&moduleName='+encodeURIComponent('<?php echo $rtr_module;?>')+'&className='+encodeURIComponent('<?php echo $rtr_class;?>')+'&methodName='+encodeURIComponent('<?php echo $rtr_method;?>')+'&currentUrl='+encodeURIComponent('<?php echo urlencode(getCurrentPageURL());?>'); img.src = srcUrl; } </script>
